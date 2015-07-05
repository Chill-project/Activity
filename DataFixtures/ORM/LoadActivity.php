<?php

/*
 * Chill is a software for social workers
 * 
 * Copyright (C) 2014-2015, Champs Libres Cooperative SCRLFS, 
 * <http://www.champs-libres.coop>, <info@champs-libres.coop>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Chill\ActivityBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Chill\ActivityBundle\Entity\Activity;
use Chill\MainBundle\DataFixtures\ORM\LoadUsers;
use Chill\ActivityBundle\DataFixtures\ORM\LoadActivityReason;
use Chill\ActivityBundle\DataFixtures\ORM\LoadActivityType;
use Chill\MainBundle\DataFixtures\ORM\LoadScopes;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Load reports into DB
 *
 * @author Champs-Libres Coop
 */
class LoadActivity extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    use \Symfony\Component\DependencyInjection\ContainerAwareTrait;
    
    /**
     * @var \Faker\Generator 
     */
    private $faker;
    
    public function __construct()
    {
        $this->faker = FakerFactory::create('fr_FR');
    } 
    
    public function getOrder()
    {
        return 16400;
    }
    
    /**
     * Return a random scope
     * 
     * @return \Chill\MainBundle\Entity\Scope
     */
    private function getRandomScope()
    {
        $scopeRef = LoadScopes::$references[array_rand(LoadScopes::$references)];
        return $this->getReference($scopeRef);
    }
    
    /**
     * Return a random activityType
     * 
     * @return \Chill\ActivityBundle\Entity\ActivityType
     */
    private function getRandomActivityType()
    {
        $typeRef = LoadActivityType::$references[array_rand(LoadActivityType::$references)];
        return $this->getReference($typeRef);
    }
    
    /**
     * Return a random activityReason
     * 
     * @return \Chill\ActivityBundle\Entity\ActivityReason
     */
    private function getRandomActivityReason()
    {
        $reasonRef = LoadActivityReason::$references[array_rand(LoadActivityReason::$references)];
        return $this->getReference($reasonRef);
    }
    
    /**
     * Return a random user
     * 
     * @return \Chill\MainBundle\Entity\User
     */
    private function getRandomUser()
    {
        $userRef = array_rand(LoadUsers::$refs);
        return $this->getReference($userRef);
    }
    
    public function newRandomActivity($person)
    {
        $activity = (new Activity())
            ->setUser($this->getRandomUser())
            ->setPerson($person)
            ->setDate($this->faker->dateTimeThisYear())
            ->setDurationTime($this->faker->dateTime(36000))
            ->setType($this->getRandomActivityType())
            ->setReason($this->getRandomActivityReason())
            ->setScope($this->getRandomScope())
            ->setAttendee($this->faker->boolean())
            ->setRemark('A remark');
        return $activity;
    }
    
    public function load(ObjectManager $manager)
    {
        $persons = $this->container->get('doctrine.orm.entity_manager')
            ->getRepository('ChillPersonBundle:Person')
            ->findAll();
        
        foreach($persons as $person) {
            $activityNbr = rand(0,3);
            for($i = 0; $i < $activityNbr; $i ++) {
                print "Creating an activity type for  : ".$person."\n";
                $activity = $this->newRandomActivity($person);
                $manager->persist($activity);
            }
        }
        $manager->flush();
    }
}
