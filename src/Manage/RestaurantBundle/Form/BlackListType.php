<?php
/**
 * Created by PhpStorm.
 * User: Yolanda
 * Date: 11-Feb-19
 * Time: 10:49 PM
 */

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Manage\RestaurantBundle\Entity\Listing;
use Manage\RestaurantBundle\Repository\ListingRepository;

class BlackListType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email', 'email')
            ->add('details')
            ->add('checkin', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array(
                    'class' => 'form-control',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy',),
                'required' => false
            ))
            ->add('listing', 'entity', array(
                'class' => 'Manage\RestaurantBundle\Entity\Listing',
                'query_builder' => function(ListingRepository $repository) {
                    return $repository->createQueryBuilder('b')
                        ->select('b')
                        ->addOrderBy('b.number');
                },
                'required' => false
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\BlackList'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'adminbundle_blacklist';
    }

}