<?php

/*
 * 
 * Copyright (C) 2015, Champs Libres Cooperative SCRLFS, <http://www.champs-libres.coop>
 * 
 * This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Chill\ActivityBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * ActivityReasonCategory
 */
class ActivityReasonCategory
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $active;
    
    /** @var ArrayCollection array of ActivityReason */
    private $reasons;
    
    public function __construct()
    {
        $this->reasons = new ArrayCollection();
    }
    
    public function __toString()
    {
        return 'ActivityReasonCategory('.$this->getName('x').')';
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param array $name
     *
     * @return ActivityReasonCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return array
     */
    public function getName($locale = null)
    {
        if ($locale) {
            if (isset($this->name[$locale])) {
                return $this->name[$locale];
            } else {
                foreach ($this->name as $name) {
                    if (!empty($name)) {
                        return $name;
                    }
                }
            }
            return '';
        } else {
            return $this->name;
        }
    }

    /**
     * Declare a category as active (or not). When a category is set
     * as unactive, all the reason have this entity as category is also
     * set as unactive
     *
     * @param boolean $active
     * @return ActivityReasonCategory
     */
    public function setActive($active)
    {
        if($this->active !== $active && !$active) {
            foreach ($this->reasons as $reason) {
                $reason->setActive($active);
            }
        }
        
        $this->active = $active;
        
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
}

