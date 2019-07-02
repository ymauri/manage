<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KasboekKasType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name1', 'text')
            ->add('name2', 'text')
            ->add('name3', 'text')
            ->add('bedrag1')
            ->add('bedrag2')
            ->add('bedrag3')
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
            'data_class' => 'Manage\RestaurantBundle\Entity\KasboekKas'
        ));
    }
}
