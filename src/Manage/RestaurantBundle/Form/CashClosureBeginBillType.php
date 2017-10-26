<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CashClosureBeginBillType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('e20', 'number', array(
                'data'  => 5
            ))
            ->add('e10', 'number', array(
                'data'  => 5
            ))
            ->add('e5', 'number', array(
                'data'  => 5
            ))
            ->add('e2', 'number', array(
                'data'  => 10
            ))
            ->add('e1', 'number', array(
                'data'  => 10
            ))
            ->add('e050', 'number', array(
                'data'  => 10
            ))
            ->add('e020', 'number', array(
                'data'  => 0
            ))
            ->add('e010', 'number', array(
                'data'  => 0
            ))
            ->add('s20', 'number', array(
                'data'  => 0
            ))
            ->add('s10', 'number', array(
                'data'  => 0
            ))
            ->add('s5', 'number', array(
                'data'  => 0
            ))
            ->add('s2', 'number', array(
                'data'  => 0
            ))
            ->add('s1', 'number', array(
                'data'  => 0
            ))
            ->add('s050', 'number', array(
                'data'  => 0
            ))
            ->add('s020', 'number', array(
                'data'  => 0
            ))
            ->add('s010', 'number' , array(
                'data'  => 0
            ))
            ->add('total','number', array(
                'read_only' =>true,
                'precision' =>2,
            ))
            ->add('extra')
            ->add('standard')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\CashClosureBeginBill'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'manage_restaurantbundle_cashclosurebeginbill';
    }
}
