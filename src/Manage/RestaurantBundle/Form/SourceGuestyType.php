<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Manage\RestaurantBundle\Entity\SourceGuestyRepository;
use Manage\RestaurantBundle\Entity\SourceGuesty;

class SourceGuestyType extends AbstractType
{
   
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\SourceGuesty'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'restaurantbundle_sourceguesty';
    }
}
