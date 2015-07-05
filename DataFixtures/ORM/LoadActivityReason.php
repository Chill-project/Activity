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
use Chill\ActivityBundle\Entity\ActivityReason;

/**
 * Description of LoadActivityReason
 *
 * @author Champs-Libres Coop
 */
class LoadActivityReason extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 16300;
    }
    
    public static $references = array();
    
    public function load(ObjectManager $manager)
    {
        $reasons = [
            [
                'name' => ['fr' => 'Recherche logement', 'en' => 'Housing research', 'nl' => 'Woning zoektoch'],
                'category' => 'cat_Housing'],
            [
                'name' => ['fr' => 'Problème avec propriétaire', 'en' => 'Landlord problems', 'nl' => 'Huisbaas problemen'],
                'category' => 'cat_Housing'],
            [
                'name' => ['fr' => 'Retard de payement', 'en' => 'Payement problems', 'nl' => 'Betalings vertragingen'],
                'category' => 'cat_Housing'],
            [
                'name' => ['fr' => 'Explication législation', 'en' => 'Legislation explanation', 'nl' => 'Legislative uitleg'],
                'category' => 'cat_Unemployment procedure'],
            [
                'name' => ['fr' => 'Coaching entretien d\'activation', 'en' => 'Interview coaching', 'nl' => 'Interview coaching'],
                'category' => 'cat_Unemployment procedure'],
            [
                'name' => ['fr' => 'Récupération des allocations', 'en' => 'Allowance recovery', 'nl' => 'Terugwinning van de uitkeringen'],
                'category' => 'cat_Unemployment procedure']
        ];
        
        foreach ($reasons as $r) {
            print "Creating activity reason : " . $r['name']['en'] . "\n";
            $activityReason = (new ActivityReason())
                ->setName(($r['name']))
                ->setActive(true)
                ->setCategory($this->getReference($r['category']));
            $manager->persist($activityReason);
            $reference = 'activity_reason_'.$r['name']['en'];
            $this->addReference($reference, $activityReason);
            static::$references[] = $reference;
        }
        
        $manager->flush();
    }
}
