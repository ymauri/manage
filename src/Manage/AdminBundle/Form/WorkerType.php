<?php

namespace Manage\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class WorkerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('position', 'choice', array(
                'choices'  => array(
                    'Chef' => 'Chef',
                    'Manager Restaurant' => 'Manager Restaurant',
                    'Manager Sky Bar' => 'Manager Sky Bar',
                    'Receptie' => 'Receptie',
                    'Chief' => 'Chief',
                ),
            ))
            ->add('isactive', 'checkbox', array(
                'required'  =>false
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\AdminBundle\Entity\Worker'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'manage_adminbundle_worker';
    }
}
