<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KasboekContantenBedragType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('e500', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('e200', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('e100', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('e50', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('e20', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('e10', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('e5', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('e2', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('e1', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('e050', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('e020', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('e010', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('e005', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('e002', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('e001', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('total', 'number', array(
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
            'data_class' => 'Manage\RestaurantBundle\Entity\KasboekContanten'
        ));
    }
}
