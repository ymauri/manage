<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TurnoverSkybarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                      ->add('omzkitchen', 'number', array(
                         
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('omzlaag', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('omzhoog', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('omzspacerent', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('omzothers', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('omzentry', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('omzparking', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('omzvouchersrek', 'number', array(
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
            ->add('ontbelevoucher', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('ontvooverkoop', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('ontkadopagina', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('ontrekening', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('onttickets', 'number', array(
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
            'data_class' => 'Manage\RestaurantBundle\Entity\TurnoverSkybar'
        ));
    }
}
