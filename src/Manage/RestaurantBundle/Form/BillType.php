<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BillType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('e500', 'number',array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e200', 'number',array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e100', 'number',array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e50', 'number',array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e20', 'number',array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e10', 'number',array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e5', 'number',array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e2', 'number',array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e1', 'number',array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e050', 'number',array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e020', 'number',array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e010', 'number',array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e005', 'number',array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('eind', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('waarvan', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\Bill'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'adminbundle_bill';
    }
}
