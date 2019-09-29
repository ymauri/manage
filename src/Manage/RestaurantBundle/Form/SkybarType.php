<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Manage\RestaurantBundle\Entity\WorkerRepository;
use Manage\RestaurantBundle\Entity\Worker;

class SkybarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dated', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array(
                    'class' => 'form-control datepicker',
                    'data-provide' => 'datepicker',
                    'readonly' => 'readonly',
                    'data-date-format' => 'dd-mm-yyyy',
                )))
            ->add('time', 'time', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
                ))
            ->add('name', 'text',array(
                'label'     => false,
                'attr'      => array(
                    'hidden'    => 'true'
                )
            ))
            ->add('details')
             ->add('userdag', 'entity', array(
                 'class' => 'Manage\RestaurantBundle\Entity\Worker',
                 'query_builder' => function(WorkerRepository $repository) {
                     return $repository->createQueryBuilder('a')
                         ->select('a')
                         ->where('a.position = \'Manager Sky Bar\'')
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
                        ->where('a.position = \'Manager Sky Bar\'')
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
            ->add('finished',null ,array(

                'attr' => array(
                    'hidden'=>true,
                )))
                
            ->add('voorgeschotentotal', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('ontvangen', 'number',array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('kasverschil', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
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
            ->add('extvouchers', 'text',array(
                'label'     => false,
                'attr'      => array(
                    'hidden'    => 'true'
                )
            ))
            ->add('extvouchersvalues', 'text',array(                
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
            ->add('voucherstotal', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('belevoucherstotal', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('kadovoucherstotal', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('ticketvoucherstotal', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('skydag', 'number',array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('skyavond', 'number',array(
                'attr'=>array('maxlength'=>3),
            ))
            ->add('skymoneydag', 'number', array(
                'precision' => 2
            ))
            ->add('skymoneyavond', 'number', array(
                'precision' => 2
            ))
            ->add('skymeandag', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('skymeanavond', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('skytotal', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
            ))
            ->add('skymoneytotal', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('skymeantotal', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('rekening', 'text',array(                
                'label'     => false,
                'attr'      => array(
                    'hidden'    => 'true'
                )
            ))
            ->add('voorverkoop', 'text',array(                
                'label'     => false,
                'attr'      => array(
                    'hidden'    => 'true'
                )
            ))
            ->add('rekeningtotal', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('voorverkooptotal', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))
            ->add('completeprofit', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' => true,),
                'precision' => 2
            ))            
            ->add('voucherday', 'number', array(
            ))
            ->add('vouchernight', 'number', array(
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\Skybar'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'adminbundle_skybar';
    }
}
