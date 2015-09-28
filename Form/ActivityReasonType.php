<?php

namespace Chill\ActivityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActivityReasonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'translatable_string')
            ->add('active', 'checkbox', array('required' => false))
            ->add('category', 'translatable_activity_reason_category')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Chill\ActivityBundle\Entity\ActivityReason'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'chill_activitybundle_activityreason';
    }
}
