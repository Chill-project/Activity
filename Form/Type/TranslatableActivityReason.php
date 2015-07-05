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

namespace Chill\ActivityBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\RequestStack;
use Chill\MainBundle\Templating\TranslatableStringHelper;

/**
 * Description of TranslatableActivityReason
 *
 * @author Champs-Libres Coop
 */
class TranslatableActivityReason extends AbstractType
{
    
    private $translatableStringHelper;
    
    public function __construct(TranslatableStringHelper $translatableStringHelper)
    {
        $this->translatableStringHelper = $translatableStringHelper;
    }
    
    public function getName()
    {
        return 'translatable_activity_reason';
    }
    
    public function getParent()
    {
        return 'entity';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $helper = $this->translatableStringHelper;
        $resolver->setDefaults(
            array(
                'class' => 'ChillActivityBundle:ActivityReason',
                'choice_label' => function($choice, $key) use ($helper) {
                    return $helper->localize($choice->getName());
                },
                'group_by' => function($choice, $key) use ($helper) {
                    return $helper->localize($choice->getCategory()->getName());
                }
            )
        );
    }
}
