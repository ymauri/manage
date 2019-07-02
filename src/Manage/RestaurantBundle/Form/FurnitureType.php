<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Manage\AdminBundle\Entity\Status;
use Manage\RestaurantBundle\Entity\Listing;

class FurnitureType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('details')
            ->add('name')
            ->add('serialnumber')
            ->add('status')
            ->add('location')
           /* ->add('status', 'entity', array(
                'class' => 'Manage\AdminBundle\Entity\Status',
                'query_builder' => function(StatusRepository $repository) {
                    return $repository->createQueryBuilder('b')
                        ->select('b')
                        ->addOrderBy('b.name');
                },
                'required' => false
            ))*/

            ->add('image', 'file', array('required' => false))

           /* ->add('location', 'entity', array(
                'class' => 'Manage\RestaurantBundle\Entity\Listing',
                'query_builder' => function(ListingRepository $repository) {
                    return $repository->createQueryBuilder('c')
                        ->select('c')
                        ->addOrderBy('c.name');
                },
                'required' => false
            ))*/
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\Furniture'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'restaurantbundle_furniture';
    }
}
