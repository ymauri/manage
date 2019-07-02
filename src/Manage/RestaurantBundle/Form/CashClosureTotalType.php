<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotNull;

class CashClosureTotalType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('xlaag', 'money', array(

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
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
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
                'attr'=>array('tabindex'=>-1,'readonly' => true,),

            ))
            
       
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
        return 'adminbundle_cashclosuretotal';
    }
}
