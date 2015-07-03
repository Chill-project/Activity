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

namespace Chill\ActivityBundle\Security\Authorization;

use Chill\MainBundle\Security\Authorization\AbstractChillVoter;
use Chill\MainBundle\Security\Authorization\AuthorizationHelper;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class ActivityVoter extends AbstractChillVoter
{
    const CREATE = 'CHILL_ACTIVITY_CREATE';
    const SEE    = 'CHILL_ACTIVITY_SEE';
    const UPDATE = 'CHILL_ACTIVITY_UPDATE';
    
    /**
     *
     * @var AuthorizationHelper
     */
    protected $helper;
    
    public function __construct(AuthorizationHelper $helper)
    {
        $this->helper = $helper;
    }
    
    protected function getSupportedAttributes()
    {
        return array(self::CREATE, self::SEE, self::UPDATE);
    }

    protected function getSupportedClasses()
    {
        return array('Chill\ActivityBundle\Entity\Activity');
    }

    protected function isGranted($attribute, $report, $user = null)
    {
        if (! $user instanceof \Chill\MainBundle\Entity\User){
            
            return false;
        }
        
        return $this->helper->userHasAccess($user, $report, $attribute);
    }
}
