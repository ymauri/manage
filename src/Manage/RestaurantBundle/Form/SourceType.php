<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Manage\RestaurantBundle\Entity\SourceGuestyRepository;
use Manage\RestaurantBundle\Entity\SourceGuesty;

class SourceType extends AbstractType
{
    private $guesty;

    public function __construct(array $list){
        $this->guesty = $list;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('details')
            ->add('isactive', 'checkbox', array(
                'required'  =>false
            ))
            ->add('extrafield', 'checkbox', array(
                'required'  =>false
            ))
            ->add('guesty', 'choice', array(
                'choices' => $this->guesty,
                'required' => true
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\Source'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'RestaurantBundle_source';
    }
}
