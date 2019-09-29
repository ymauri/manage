<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Manage\RestaurantBundle\Entity\WorkerRepository;
use Manage\RestaurantBundle\Entity\Worker;

class ReceptionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {   
        
        $builder
            ->add('name', 'text', array(
                'attr'=>array(
                    'value'=>'Receptie Kassa Cash & Log',
                    'hidden'=>true,
                )
            ))
            ->add('details','textarea')
            ->add('dated', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array(
                    'class' => 'form-control datepicker',
                    'data-provide' => 'datepicker',
                    'readonly' => true,
                    'data-date-format' => 'dd-mm-yyyy',
                )))
              
            ->add('vdstart', 'number', array(
                    'attr'   => array(
                        
                        'maxlength'=>'6'
                    )))
            ->add('vdend', 'number', array(
                'attr'   => array(
                    'type'=>'number',
                    'maxlength'=>'6'
                )))
            ->add('vdamount', 'number', array(

                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
            ))
            ->add('vnstart', 'number', array(
                'attr'   => array(
                    'type'=>'number',
                    'maxlength'=>'6'
                )))
            ->add('vnend', 'number', array(
                'attr'   => array(
                    'type'=>'number',
                    'maxlength'=>'6'
                )))
            ->add('vnamount', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
            ))
            ->add('freevoucher', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('generalamount', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
            ))
            ->add('halfprice', 'number', array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('profit', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('voucher', null, array(

                'attr'      => array(
                    'hidden'    => 'true',
                    'tabindex'=>-1,
                    'readonly'=>true,
                )
            ))
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
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('parkingtotal', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('completeprofit', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
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
            ->add('voorgeschoten', 'text',array(
                'label'     => false,
                'attr'      => array(
                    'hidden'    => 'true'
                )
            ))
            ->add('voorgeschotentotal', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('ontvangen', 'number',array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('kasverschil', 'number', array(
                'attr'=>array('tabindex'=>-1, 'readonly' => true,),
                'precision' => 2
            ))
            ->add('userdag', 'entity', array(
                'class' => 'Manage\RestaurantBundle\Entity\Worker',
                'query_builder' => function(WorkerRepository $repository) {
                    return $repository->createQueryBuilder('a')
                        ->select('a')
                        ->where('a.position = \'Receptie\'')
                        ->andWhere('a.isactive = \'1\' ')
                        ->addOrderBy('a.name');
                },
                'required' => false
            ))
            ->add('useravond', 'entity', array(
                'class' => 'Manage\RestaurantBundle\Entity\Worker',
                'query_builder' => function(WorkerRepository $repository) {
                    return $repository->createQueryBuilder('a')
                        ->select('a')
                        ->where('a.position = \'Receptie\'')
                        ->andWhere('a.isactive = \'1\' ')
                        ->addOrderBy('a.name');
                },
                'required' => false
            ))
            
            ->add('updated', 'date',array(
                'widget' => 'single_text',
            'attr'=>array(
                'hidden'=>true,
            )))
            ->add('finished', null ,array(
                'attr' => array(
                    'hidden'=>true,
                )))
            ->add('time', 'time', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
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
        return 'adminbundle_reception';
    }    
    
}
