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

namespace Chill\ActivityBundle\Tests\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use Chill\ActivityBundle\Form\Type\TranslatableActivityReason;
use Chill\MainBundle\Templating\TranslatableStringHelper;
use Symfony\Component\Form\PreloadedExtension;

/**
 * Test translatableActivityReason
 *
 * @author Julien Fastré <julien.fastre@champs-libres.coop>
 * @author Champs Libres <info@champs-libres.coop>
 */
class TranslatableActivityReasonTest extends TypeTestCase
{
    /**
     *
     * @var Prophecy\Prophet 
     */
    private static $prophet;
    
    public function setUp()
    {
        parent::setUp();
        
        
    }
    
    protected function getExtensions()
    {
        $entityType = $this->getEntityType();
        
        return array(new PreloadedExtension(array(
           'entity' => $entityType
        ), array()));
    }
    
    
    public function testSimple()
    {
        $translatableActivityReasonType = new TranslatableActivityReason(
              $this->getTranslatableStringHelper()
              );
        
        $this->markTestSkipped("See issue 651");
    }
    
    /**
     * 
     * @param string $locale
     * @param string $fallbackLocale
     * @return TranslatableStringHelper
     */
    protected function getTranslatableStringHelper($locale = 'en', 
          $fallbackLocale = 'en')
    {
        $prophet = new \Prophecy\Prophet;
        $requestStack = $prophet->prophesize();
        $request = $prophet->prophesize();
        $translator = $prophet->prophesize();
        
        $request->willExtend('Symfony\Component\HttpFoundation\Request');
        $request->getLocale()->willReturn($fallbackLocale);
        
        $requestStack->willExtend('Symfony\Component\HttpFoundation\RequestStack');
        $requestStack->getCurrentRequest()->will(function () use ($request) {
            return $request;
        });
        
        $translator->willExtend('Symfony\Component\Translation\Translator');
        $translator->getFallbackLocales()->willReturn($locale);
        
        return new TranslatableStringHelper($requestStack->reveal(), 
              $translator->reveal());
        
    }
    
    /**
     * 
     * @return \Symfony\Bridge\Doctrine\Form\Type\EntityType
     */
    protected function getEntityType()
    {
        $managerRegistry = (new \Prophecy\Prophet())->prophesize();
        
        $managerRegistry->willImplement('Doctrine\Common\Persistence\ManagerRegistry');
        
        return new \Symfony\Bridge\Doctrine\Form\Type\EntityType($managerRegistry->reveal());
    }
}
