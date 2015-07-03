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

use Chill\MainBundle\Entity\Scope;
use Chill\MainBundle\Entity\User;
use Chill\ActivityBundle\Entity\ActivityReason;
use Chill\ActivityBundle\Entity\ActivityType;
use Chill\PersonBundle\Entity\Person;
use Chill\MainBundle\Entity\HasCenterInterface;
use Chill\MainBundle\Entity\HasScopeInterface;

/**
 * Activity
 */
class Activity implements HasCenterInterface, HasScopeInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var User
     */
    private $user;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var \DateTime
     */
    private $durationTime;

    /**
     * @var string
     */
    private $remark;

    /**
     * @var boolean
     */
    private $attendee;

    /**
     * @var ActivityReason
     */
    private $reason;

    /**
     * @var ActivityType
     */
    private $type;

    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var Person
     */
    private $person;


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
     * Set user
     *
     * @param User $user
     *
     * @return Activity
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Activity
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set durationTime
     *
     * @param \DateTime $durationTime
     *
     * @return Activity
     */
    public function setDurationTime($durationTime)
    {
        $this->durationTime = $durationTime;

        return $this;
    }

    /**
     * Get durationTime
     *
     * @return \DateTime
     */
    public function getDurationTime()
    {
        return $this->durationTime;
    }

    /**
     * Set remark
     *
     * @param string $remark
     *
     * @return Activity
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * Get remark
     *
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Set attendee
     *
     * @param boolean $attendee
     *
     * @return Activity
     */
    public function setAttendee($attendee)
    {
        $this->attendee = $attendee;

        return $this;
    }

    /**
     * Get attendee
     *
     * @return boolean
     */
    public function getAttendee()
    {
        return $this->attendee;
    }

    /**
     * Set reason
     *
     * @param ActivityReason $reason
     *
     * @return Activity
     */
    public function setReason(ActivityReason $reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return ActivityReason
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set type
     *
     * @param ActivityType $type
     *
     * @return Activity
     */
    public function setType(ActivityType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return ActivityType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set scope
     *
     * @param Scope $scope
     *
     * @return Activity
     */
    public function setScope(Scope $scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return Scope
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set person
     *
     * @param Person $person
     *
     * @return Activity
     */
    public function setPerson(Person $person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }
    
    /**
     * get the center
     * 
     * center is extracted from person
     * 
     * @return \Chill\MainBundle\Entity\Center
     */
    public function getCenter()
    {
        return $this->person->getCenter();
    }
}

