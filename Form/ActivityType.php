<?php

namespace Chill\ActivityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Chill\MainBundle\Form\Type\AppendScopeChoiceTypeTrait;
use Chill\MainBundle\Security\Authorization\AuthorizationHelper;
use Doctrine\Common\Persistence\ObjectManager;
use Chill\MainBundle\Templating\TranslatableStringHelper;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Chill\MainBundle\Entity\User;

class ActivityType extends AbstractType
{
    
    use AppendScopeChoiceTypeTrait;
    
    /**
     * the user running this form
     *
     * @var User
     */
    protected $user;
    
    /**
     *
     * @var AuthorizationHelper
     */
    protected $authorizationHelper;
    
    /**
     *
     * @var ObjectManager
     */
    protected $om;
    
    /**
     *
     * @var TranslatableStringHelper
     */
    protected $translatableStringHelper;
    
    public function __construct(TokenStorageInterface $tokenStorage, 
        AuthorizationHelper $authorizationHelper, ObjectManager $om, 
        TranslatableStringHelper $translatableStringHelper) 
    {
        if (!$tokenStorage->getToken()->getUser() instanceof User) {
            throw new \RuntimeException("you should have a valid user");
        }
        $this->user = $tokenStorage->getToken()->getUser();
        $this->authorizationHelper = $authorizationHelper;
        $this->om = $om;
        $this->translatableStringHelper = $translatableStringHelper;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date', array(
               'required' => true, 
               'widget' => 'single_text', 
               'format' => 'dd-MM-yyyy')
            )
            ->add('durationTime')
            ->add('remark', 'textarea', array(
               'required' => false,
               'empty_data' => ''
            ))            
            ->add('attendee', 'choice', array(
               'expanded' => true,
               'required' => false,
               'choices' => array(
                  true => 'present',
                  false => 'not present'
               )
            ))
            ->add('user')
            //->add('scope')
            //->add('reason')
            //->add('type')
            //->add('person')
        ;
        
        $this->appendScopeChoices($builder, $options['role'], 
              $options['center'], $this->user, $this->authorizationHelper, 
              $this->translatableStringHelper, $this->om);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Chill\ActivityBundle\Entity\Activity'
        ));
        
        $this->appendScopeChoicesOptions($resolver);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'chill_activitybundle_activity';
    }
}
