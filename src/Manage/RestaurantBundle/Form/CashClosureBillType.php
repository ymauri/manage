<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CashClosureBillType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('e500', 'number')
            ->add('e200', 'number')
            ->add('e100', 'number')
            ->add('e50', 'number')
            ->add('e20', 'number')
            ->add('e10', 'number')
            ->add('e5', 'number')
            ->add('e2', 'number')
            ->add('e1', 'number')
            ->add('e050', 'number')
            ->add('e020', 'number')
            ->add('e010', 'number')
            ->add('e005', 'number')
            ->add('eind', 'number', array(
                'read_only' =>true,
                'attr'      =>array(
                    'value' => 0.00
                )
            ))
            ->add('waarvan', 'number', array(
                'read_only'=>true,
                'attr'      =>array(
                    'value' => 0.00
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\CashClosureBill'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'manage_restaurantbundle_bill';
    }
}
