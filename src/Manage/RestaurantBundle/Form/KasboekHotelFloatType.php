<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KasboekHotelFloatType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('e500')
            ->add('e200')
            ->add('e100')
            ->add('e50')
            ->add('e20')
            ->add('e10')
            ->add('e5')
            ->add('waarde')
            ->add('totalmoney','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('bank1')
            ->add('bank2')
            ->add('bank3')
            ->add('bank4')
            ->add('contant','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('kasverschil','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\KasboekHotelFloat'
        ));
    }
}
