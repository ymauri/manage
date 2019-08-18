<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Manage\AdminBundle\Entity\WorkerRepository;
use Manage\RestaurantBundle\Repository\FolderRepository;

class ReportIssueType extends AbstractType
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
                'attr' => array(
                    'readonly' => true,)))
            ->add('reportedat', 'time', array(
                'widget' => 'single_text',
                'required' => false,
                'attr' => array(
                    'readonly' => true,

                )
            ))
            ->add('details')
            ->add('location')
            ->add('furniture')
            ->add('image', 'file')
            ->add('priority', "choice", array(
                'choices' => array(
                    'Normaal' => 'Normaal',
                    'Laag' => 'Laag',
                    'Hoog' => 'Hoog',
                ),
                'required' => true
            ))
            ->add('reporter', 'entity', array(
                'class' => 'Manage\AdminBundle\Entity\Worker',
                'query_builder' => function (WorkerRepository $repository) {
                    return $repository->createQueryBuilder('b')
                        ->select('b')
                        ->where('b.isactive = \'1\' ')
                        ->addOrderBy('b.name');
                },
                'required' => true,
                'multiple' => false
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\ReportIssue'
        ));
    }
}
