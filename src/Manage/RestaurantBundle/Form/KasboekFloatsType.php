<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KasboekFloatsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('wstandar')
            ->add('astandar')
            ->add('bstandar','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('wextra1')
            ->add('aextra1')
            ->add('bextra1','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('wextra2')
            ->add('aextra2')
            ->add('bextra2','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('wextra3')
            ->add('aextra3')
            ->add('bextra3','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('total','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\KasboekFloats'
        ));
    }
}
