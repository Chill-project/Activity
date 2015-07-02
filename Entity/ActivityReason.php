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

use Chill\ActivityBundle\Entity\ActivityReasonCategory;

/**
 * ActivityReason
 */
class ActivityReason
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var array
     */
    private $name;

    /**
     * @var ActivityReasonCategory
     */
    private $category;

    /**
     * @var boolean
     */
    private $active;


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
     * @return ActivityReason
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return array | string
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
     * Set category
     *
     * @param ActivityReasonCategory $category
     *
     * @return ActivityReason
     */
    public function setCategory(ActivityReasonCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return ActivityReasonCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return ActivityReason
     */
    public function setActive($active)
    {
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

