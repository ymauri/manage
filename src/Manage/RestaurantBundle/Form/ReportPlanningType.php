<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Manage\RestaurantBundle\Entity\WorkerRepository;
use Manage\RestaurantBundle\Repository\FolderRepository;
use Manage\RestaurantBundle\Controller\Nomenclator;

class ReportPlanningType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('details', 'textarea', array('required' => true))
            ->add('folder')
            ->add('priority', "choice", array(
                'choices' => array(
                    'Normaal' => 'Normaal',
                    'Laag' => 'Laag',
                    'Hoog' => 'Hoog',
                ),
                'required' => true
            ))
            ->add('furniture')
            ->add('begins', 'date', array('widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array(
                    'class' => 'form-control datepicker',
                    'data-provide' => 'datepicker',
                    'readonly' => true,
                    'data-date-format' => 'dd-mm-yyyy',)))
            ->add('ends', 'date', array('widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array(
                    'class' => 'form-control datepicker',
                    'data-provide' => 'datepicker',
                    'readonly' => true,
                    'data-date-format' => 'dd-mm-yyyy',)))
            ->add('pathimage', 'hidden')
            ->add('image', 'file', array('required'=>false))
            ->add('frequency', "choice", array(
                'choices' => array(
                    Nomenclator::PLANNING_WEEKLY => ucwords(Nomenclator::PLANNING_WEEKLY),
                    Nomenclator::PLANNING_MONTHLY => ucwords(Nomenclator::PLANNING_MONTHLY),
                    Nomenclator::PLANNING_QUATERLY => ucwords(Nomenclator::PLANNING_QUATERLY),
                    Nomenclator::PLANNING_BIANNUAL => ucwords(Nomenclator::PLANNING_BIANNUAL),
                    Nomenclator::PLANNING_YEARLY => ucwords(Nomenclator::PLANNING_YEARLY),
                ),
                'required' => true
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\ReportPlanning'
        ));
    }
}
