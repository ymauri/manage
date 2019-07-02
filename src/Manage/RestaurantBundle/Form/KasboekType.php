<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KasboekType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'attr'=>array(
                    'value'=>'Kasboek',
                    'hidden'=>true,
                )
            ))
            ->add('details')
            ->add('dated', 'datetime')
            ->add('updated', 'datetime')
            //->add('finished', 'datetime')
            ->add('totalsalarissenkas','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('totalinkas','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('kasverschil','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('eindsaldo','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('beginsaldo','number', array( 'attr'=>array(), 'precision' => 2))

        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\Kasboek'
        ));
    }
}
