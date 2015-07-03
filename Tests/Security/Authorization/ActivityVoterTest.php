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

namespace Chill\ActivityBundle\Tests\Security\Authorization;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Chill\MainBundle\Entity\Center;
use Chill\MainBundle\Entity\User;
use Chill\MainBundle\Entity\Scope;
use Chill\MainBundle\Test\PrepareUserTrait;
use Chill\MainBundle\Test\PrepareCenterTrait;
use Chill\MainBundle\Test\PrepareScopeTrait;
use Chill\PersonBundle\Test\PreparePersonTrait;
use Chill\ActivityBundle\Test\PrepareActivityTrait;

/**
 * 
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 */
class ActivityVoterTest extends KernelTestCase
{
    use PrepareUserTrait, PrepareCenterTrait, PrepareScopeTrait, 
            PreparePersonTrait, PrepareActivityTrait;
    
    /**
     *
     * @var \Chill\PersonBundle\Security\Authorization\PersonVoter
     */
    protected $voter;
    
    /**
     *
     * @var \Prophecy\Prophet
     */
    protected $prophet;
    
    public function setUp()
    {
        static::bootKernel();
        $this->voter = static::$kernel->getContainer()
              ->get('chill.activity.security.authorization.activity_voter');
        $this->prophet = new \Prophecy\Prophet();
    }
    
    public function testNullUser()
    {
        $token = $this->prepareToken();
        $center = $this->prepareCenter(1, 'center');
        $person = $this->preparePerson($center);
        $scope = $this->prepareScope(1, 'default');
        $activity = $this->prepareActivity($scope, $person);
        
        $this->assertEquals(
                VoterInterface::ACCESS_DENIED,
                $this->voter->vote($token, $activity, array('CHILL_ACTIVITY_SEE')), 
                "assert that a null user is not allowed to see"
                );
    }
    
    /**
     * 
     * @dataProvider dataProvider_testVoteAction
     * @param type $expectedResult
     * @param User $user
     * @param Scope $scope
     * @param Center $center
     * @param string $attribute
     * @param string $message
     */
    public function testVoteAction($expectedResult, User $user, Scope $scope, 
            Center $center, $attribute, $message)
    {
        $token = $this->prepareToken($user);
        $activity = $this->prepareActivity($scope, $this->preparePerson($center));
        
        $this->assertEquals(
                $expectedResult,
                $this->voter->vote($token, $activity, array($attribute)),
                $message
            );
    }
    
    public function dataProvider_testVoteAction()
    {
        $centerA = $this->prepareCenter(1, 'center A');
        $centerB = $this->prepareCenter(2, 'center B');
        $scopeA = $this->prepareScope(1, 'scope default');
        $scopeB = $this->prepareScope(2, 'scope B');
        $scopeC = $this->prepareScope(3, 'scope C');
        
        $userA = $this->prepareUser(array(
            array(
                'center' => $centerA, 
                'permissionsGroup' => array(
                    ['scope' => $scopeB, 'role' => 'CHILL_ACTIVITY_CREATE'],
                    ['scope' => $scopeA, 'role' => 'CHILL_ACTIVITY_SEE']
                )
            ),
            array(
               'center' => $centerB,
               'permissionsGroup' => array(
                     ['scope' => $scopeA, 'role' => 'CHILL_ACTIVITY_CREATE'],
                     ['scope' => $scopeC, 'role' => 'CHILL_ACTIVITY_CREATE']
               )
            )
            
        ));
        
        return array(
            array(
                VoterInterface::ACCESS_GRANTED,
                $userA,
                $scopeB,
                $centerA,
                'CHILL_ACTIVITY_CREATE',
                'assert that a user granted with same rights'
            ),
            array(
                VoterInterface::ACCESS_GRANTED,
                $userA,
                $scopeB,
                $centerA,
                'CHILL_ACTIVITY_SEE',
                'assert that a user granted with inheritance rights'
            ),
            array(
                VoterInterface::ACCESS_DENIED,
                $userA,
                $scopeC,
                $centerA,
                'CHILL_ACTIVITY_SEE',
                'assert that a suer is denied if he is not granted right on this center'
                
            )
        );
    }
    
    /**
     * prepare a token interface with correct rights
     * 
     * if $permissions = null, user will be null (no user associated with token
     * 
     * @return \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    protected function prepareToken(User $user = null)
    {        
        $token = $this->prophet->prophesize();
        $token
            ->willImplement('\Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        if ($user === NULL) {
            $token->getUser()->willReturn(null);
        } else {
            $token->getUser()->willReturn($user);
        }
        
        return $token->reveal();
    }
}
