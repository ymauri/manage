<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Manage\AdminBundle\Entity\WorkerRepository;
use Manage\AdminBundle\Entity\Worker;


class HotelType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', 'text', array(
                'attr' => array(
                    'value' => 'Hotel Kassa Cash & Log',
                    'hidden' => true,
                )
            ))
            ->add('details', 'textarea', array('attr' => array(
                    'hidden' => true,)))
            ->add('dated', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array(
                    'class' => 'form-control datepicker',
                    'data-provide' => 'datepicker',
                    'readonly' => true,
                    'data-date-format' => 'dd-mm-yyyy',)))
            ->add('userdoor', 'entity', array(
                'class' => 'Manage\AdminBundle\Entity\Worker',
                'query_builder' => function(WorkerRepository $repository) {
                    return $repository->createQueryBuilder('b')
                        ->select('b')
                        ->where('b.position = \'Receptie\'')
                        ->andWhere('b.isactive = \'1\' ')
                        ->addOrderBy('b.name');
                },
                'required' => false
            ))
           
            ->add('updated', 'date',array(
                'widget' => 'single_text',
                'attr'=>array(
                    'hidden'=>true, )))
            ->add('finished', 'date',array(
                'widget' => 'single_text',
                'attr'=>array(
                    'hidden'=>true, )));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\Hotel'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'RestaurantBundle_hotel';
    }

}
