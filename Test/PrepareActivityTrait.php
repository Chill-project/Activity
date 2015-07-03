<?php

/*
 * Copyright (C) 2015 Julien Fastré <julien.fastre@champs-libres.coop>
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

namespace Chill\ActivityBundle\Test;

use Chill\ActivityBundle\Entity\Activity;
use Chill\MainBundle\Entity\Scope;
use Chill\PersonBundle\Entity\Person;

/**
 * Prepare entities useful in tests
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
trait PrepareActivityTrait
{
    /**
     * Return an activity with a scope and a person inside
     * 
     * @param Scope $scope
     * @param Person $person
     * @return Activity
     */
    public function prepareActivity(Scope $scope, Person $person)
    {
        return (new Activity())
            ->setScope($scope)
            ->setPerson($person)
                ;
    }
    
}
