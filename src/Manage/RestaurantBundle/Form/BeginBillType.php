<?php

namespace Manage\RestaurantBundle\Form;

use Doctrine\Tests\Common\Annotations\True;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotNull;

class BeginBillType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('e20', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e10', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e5', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e2', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e1', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e050', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e020', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('e010', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('s20', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('s10', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('s5', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('s2', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('s1', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('s050', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('s020', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('s010', 'number' , array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('total','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' =>2,
            ))
            ->add('extra', 'choice', array(
                'choices' => array(
                    '1' => 'Ja',
                    '0' => 'Nee'
                ),
                'required'    => true,
                'expanded' => true,
                'multiple' => false,
            ))
            ->add('hascash', 'choice', array(
                'choices' => array(
                    '1' => 'Ja',
                    '0' => 'Nee'
                ),
                'required'    => true,
                'expanded' => true,
                'multiple' => false,
            ))
            ->add('standard', 'choice', array(
                'choices' => array(
                    '1' => 'Ja',
                    '0' => 'Nee'
                ),
                'required'    => true,
                'expanded' => true,
                'multiple' => false,
            ))
           // ->add('standard', null, array(
        //'constraints' => array(new NotNull())))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\BeginBill'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'adminbundle_beginbill';
    }
}
