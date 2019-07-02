<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ListingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('details')
            ->add('number')
            ->add('level')
            ->add('image', 'file', array('required' => false))
            ->add('activeforrent', 'checkbox', array(
                'required'  =>false
            ))
            ->add('minprice')
            ->add('maxprice')
            ->add('type')
            ->add('priority')

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\Listing'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'restaurantbundle_listing';
    }
}
