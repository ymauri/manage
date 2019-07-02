<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReceptionVoucherType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('details')
            ->add('isactive', 'checkbox', array(
                'required'  =>false
            ))
            ->add('forreception', 'checkbox', array(
                'required'  =>false
            ))
            ->add('isext', 'checkbox', array(
                'required'  =>false
            ))
            ->add('value')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\ReceptionVoucher'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'manage_restaurantbundle_receptionvoucher';
    }
}
