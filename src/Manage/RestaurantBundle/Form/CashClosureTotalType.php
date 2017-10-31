<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CashClosureTotalType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('xlaag', 'number', array(
                'precision'=>2,
                //Buscar c'omo impedir que se escriban letras
            ))
            ->add('xkitchen', 'number', array(
                'precision'=>2,))
            ->add('xhoog', 'number', array(
                'precision'=>2,))
            ->add('xparking', 'number', array(
                'precision'=>2,))
            ->add('xentry', 'number', array(
                'precision'=>2,))
            ->add('xspacesrent', 'number', array(
                'precision'=>2,))
            ->add('xothers', 'number', array(
                'precision'=>2,))
            ->add('xtotal', 'number', array(
                'read_only' =>true,
                'attr'      =>array(
                    'value' => 0.00
                )
            ))
            ->add('zlaag', 'number', array(
                'precision'=>2,))
            ->add('zkitchen', 'number', array(
                'precision'=>2,))
            ->add('zhoog', 'number', array(
                'precision'=>2,))
            ->add('zparking', 'number', array(
                'precision'=>2,))
            ->add('zentry', 'number', array(
                'precision'=>2,))
            ->add('zspacesrent', 'number', array(
                'precision'=>2,))
            ->add('zothers', 'number', array(
                'precision'=>2,))
            ->add('ztotal', 'number', array(
                'read_only' =>true,
                'attr'      =>array(
                    'value' => 0.00
                )
            ))
            ->add('suitesapart', 'checkbox',array(
                'mapped'=>false))
            ->add('laagdag','number', array(
                'precision'=>2,))
            ->add('laagavond','number', array(
                'precision'=>2,))
            ->add('hoogdag','number', array(
                'precision'=>2,))
            ->add('hoogavond','number', array(
                'precision'=>2,))
            ->add('suites','number', array(
                'precision'=>2,
                'read_only' =>true,))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\CashClosureTotal'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'manage_restaurantbundle_cashclosuretotal';
    }
}
