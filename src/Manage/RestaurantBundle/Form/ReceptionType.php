<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Manage\RestaurantBundle\Entity\ReceptionVoucher;
use Doctrine\ORM\EntityManager;

class ReceptionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('details')
            ->add('dated', 'date', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',               
                'attr' => array(
                    'class' => 'form-control datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'yyyy-mm-dd'
                )))
            ->add('vdstart', 'number')
            ->add('vdend', 'number')
            ->add('vdamount', 'number', array(
                'read_only' =>true,
            ))
            ->add('vnstart', 'number')
            ->add('vnend', 'number')
            ->add('vnamount', 'number', array(
                'read_only' =>true,
            ))
            ->add('freevoucher', 'number')
            ->add('generalamount', 'number', array(
                'read_only' =>true,
            ))
            ->add('halfprice', 'number')
            ->add('profit', 'number', array(
                'read_only' =>true,
            ))
            ->add('voucher')
            ->add('giftvouchers', 'text',array(
                'label'     => false,
                'attr'      => array(
                    'hidden'    => 'true'
                )
            ))
            ->add('giftvouchersvalues', 'text',array(                
                'label'     => false,
                'attr'      => array(
                    'hidden'    => 'true'
                )
            ))
            ->add('giftvoucherstotal', 'number', array(
                'read_only' => true,
            ))
            ->add('parkingtotal', 'number', array(
                'read_only' => true,
            ))
            ->add('completeprofit', 'number')
            ->add('othersales', 'number')
            ->add('parking', 'text',array(
                'label'     => false,
                'attr'      => array(
                    'hidden'    => 'true'
                )
            ))
            ->add('parkingvalues', 'text',array(
                'label'     => false,
                'attr'      => array(
                    'hidden'    => 'true'
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
            'data_class' => 'Manage\RestaurantBundle\Entity\Reception'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'manage_restaurantbundle_reception';
    }    
    
}
