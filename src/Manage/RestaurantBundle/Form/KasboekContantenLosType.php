<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KasboekContantenLosType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('e500')
            ->add('e200')
            ->add('e100')
            ->add('e50')
            ->add('e20')
            ->add('e10')
            ->add('e5')
            ->add('e2')
            ->add('e1')
            ->add('e050')
            ->add('e020')
            ->add('e010')
            ->add('e005')
            ->add('e002')
            ->add('e001')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\KasboekContanten'
        ));
    }
}
