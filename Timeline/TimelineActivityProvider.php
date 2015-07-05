<?php

/*
 * Chill is a software for social workers
 * Copyright (C) 2015 Champs Libres <info@champs-libres.coop>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Chill\ActivityBundle\Timeline;

use Chill\MainBundle\Timeline\TimelineProviderInterface;
use Doctrine\ORM\EntityManager;
use Chill\MainBundle\Security\Authorization\AuthorizationHelper;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Role\Role;
use Doctrine\ORM\Mapping\ClassMetadata;
use Chill\PersonBundle\Entity\Person;
use Chill\MainBundle\Entity\Scope;

/**
 * Provide activity for inclusion in timeline
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 * @author Champs Libres <info@champs-libres.coop>
 */
class TimelineActivityProvider implements TimelineProviderInterface
{
    
    /**
     *
     * @var EntityManager
     */
    protected $em;
    
    /**
     *
     * @var AuthorizationHelper
     */
    protected $helper;
    
    /**
     *
     * @var \Chill\MainBundle\Entity\User 
     */
    protected $user;
    
    public function __construct(EntityManager $em, AuthorizationHelper $helper,
            TokenStorage $storage)
    {
        $this->em = $em;
        $this->helper = $helper;
        
        if (!$storage->getToken()->getUser() instanceof \Chill\MainBundle\Entity\User)
        {
            throw new \RuntimeException('A user should be authenticated !');
        }
        
        $this->user = $storage->getToken()->getUser();
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function fetchQuery($context, array $args)
    {
        $this->checkContext($context);
        
        $metadataActivity = $this->em->getClassMetadata('ChillActivityBundle:Activity');
        $metadataPerson = $this->em->getClassMetadata('ChillPersonBundle:Person');
        
        return array(
           'id' => $metadataActivity->getTableName()
                .'.'.$metadataActivity->getColumnName('id'),
           'type' => 'activity',
           'date' => $metadataActivity->getTableName()
                .'.'.$metadataActivity->getColumnName('date'),
           'FROM' => $this->getFromClause($metadataActivity, $metadataPerson),
           'WHERE' => $this->getWhereClause($metadataActivity, $metadataPerson,
                   $args['person'])
        );
    }
    
    private function getWhereClause(ClassMetadata $metadataActivity, 
            ClassMetadata $metadataPerson, Person $person)
    {
        $role = new Role('CHILL_ACTIVITY_SEE');
        $reachableCenters = $this->helper->getReachableCenters($this->user, 
                $role);
        $associationMapping = $metadataActivity->getAssociationMapping('person');
        
        // we start with activities having the person_id linked to person 
        // (currently only context "person" is supported)
        $whereClause = sprintf('%s = %d',
                 $associationMapping['joinColumns'][0]['name'],
                 $person->getId());
        
        // we add acl (reachable center and scopes)
        $centerAndScopeLines = array();
        foreach ($reachableCenters as $center) {
            $reachablesScopesId = array_map(
                    function(Scope $scope) { return $scope->getId(); },
                    $this->helper->getReachableScopes($this->user, $role, 
                        $person->getCenter())
                );
                    
            $centerAndScopeLines[] = sprintf('(%s = %d AND %s IN (%s))',
                    $metadataPerson->getTableName().'.'.
                        $metadataPerson->getAssociationMapping('center')['joinColumns'][0]['name'],
                    $center->getId(),
                    $metadataActivity->getTableName().'.'.
                        $metadataActivity->getAssociationMapping('scope')['joinColumns'][0]['name'],
                    implode(',', $reachablesScopesId));
            
        }
        $whereClause .= ' AND ('.implode(' OR ', $centerAndScopeLines).')';
        
        return $whereClause;
    }
    
    private function getFromClause(ClassMetadata $metadataActivity,
            ClassMetadata $metadataPerson)
    {
        $associationMapping = $metadataActivity->getAssociationMapping('person');
        
        return $metadataActivity->getTableName().' JOIN '
            .$metadataPerson->getTableName().' ON '
            .$metadataPerson->getTableName().'.'.
                $associationMapping['joinColumns'][0]['referencedColumnName']
            .' = '
            .$associationMapping['joinColumns'][0]['name']
            ;
    }

    /**
     * 
     * {@inheritDoc}
     */
    public function getEntities(array $ids)
    {
        $activities = $this->em->getRepository('ChillActivityBundle:Activity')
              ->findBy(array('id' => $ids));
        
        $result = array();
        foreach($activities as $activity) {
            $result[$activity->getId()] = $activity;
        }
        
        return $result;
    }

    /**
     * 
     * {@inheritDoc}
     */
    public function getEntityTemplate($entity, $context, array $args)
    {
        $this->checkContext($context);
        
        return array(
           'template' => 'ChillActivityBundle:Timeline:activity_person_context.html.twig',
           'template_data' => array(
              'activity' => $entity,
              'person' => $args['person'],
              'user' => $entity->getUser()
           )
        );
    }

    /**
     * 
     * {@inheritDoc}
     */
    public function supportsType($type)
    {
        return $type === 'activity';
    }
    
    /**
     * check if the context is supported
     * 
     * @param string $context
     * @throws \LogicException if the context is not supported
     */
    private function checkContext($context)
    {
        if ($context !== 'person') {
            throw new \LogicException("The context '$context' is not "
                  . "supported. Currently only 'person' is supported");
        }
    }

}
