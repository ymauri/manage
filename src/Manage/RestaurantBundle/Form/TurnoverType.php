<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Manage\AdminBundle\Entity\WorkerRepository;
use Manage\AdminBundle\Entity\Worker;

class TurnoverType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'attr'=>array('Turnover',
                    'hidden'=>true,
                )
            ))
            
            ->add('dated', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array(
                    'class' => 'form-control datepicker',
                    'data-provide' => 'datepicker',
                    'readonly' => true,
                    'data-date-format' => 'dd-mm-yyyy',
                )))
            ->add('chief', 'entity', array(
                'class' => 'Manage\AdminBundle\Entity\Worker',
                'query_builder' => function(WorkerRepository $repository) {
                    return $repository->createQueryBuilder('a')
                        ->select('a')
                        ->where('a.position = \'Chief\'')
                        ->andWhere('a.isactive = \'1\' ')
                        ->addOrderBy('a.name');
                },
                'required' => false
            ))
            ->add('finished',null ,array(
                'widget' => 'single_text',
                'attr' => array(
                    'hidden'=>true,
            )))

            ->add('omzkitchendag', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('omzkitchenavond', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            
            ->add('omzbeverageps', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('omzbeveragedag', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('omzbeverageavond', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('ontdebitcard', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('ontcreditcard', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('ontbelevoucher', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('ontvooverkoop', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('ontkadopagina', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('ontrekening', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
            ->add('onttickets', 'number', array(
                'attr' => array('readonly'=>true, 'tabindex'=>'-1')
            ))
                     
            
            ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\Turnover'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'adminbundle_turnover';
    }
}
