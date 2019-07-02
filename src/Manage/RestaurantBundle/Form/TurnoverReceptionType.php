<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TurnoverReceptionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('omzvoucher', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('omzparking', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('omzoverig', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('omztotal', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('ontdebitcard', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('ontcreditcard', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('onttotal', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\TurnoverReception'
        ));
    }
}
