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
use Chill\ActivityBundle\Entity\ActivityReasonCategory;
/**
 * Description of LoadActivityReasonCategory
 *
 * @author Champs-Libres Coop
 */
class LoadActivityReasonCategory extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 16200;
    }
    
    public function load(ObjectManager $manager)
    {
        $categs = [
            ['name' =>
                ['fr' => 'Logement', 'en' => 'Housing', 'nl' => 'Woning']],
            ['name' =>
                ['fr' => 'Démarches chômage', 'en' => 'Unemployment procedure', 'nl' => 'Werkloosheid werkwijze']],
        ];
            
        foreach ($categs as $c) {
            print "Creating activity reason category : " . $c['name']['en'] . "\n";
            $activityReasonCategory = (new ActivityReasonCategory())
                ->setName(($c['name']))
                ->setActive(true);
            $manager->persist($activityReasonCategory);
            $this->addReference(
              'cat_'.$c['name']['en'],
              $activityReasonCategory);
        }
        
        $manager->flush();
    }
}
