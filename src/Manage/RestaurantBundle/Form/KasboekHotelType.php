<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KasboekHotelType extends AbstractType
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
                    'value'=>'Hotel Kasboek',
                    'hidden'=>true,
                )
            ))
            ->add('details')
            ->add('dated', 'date')
            
            ->add('overnachtingen','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('parking','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('toeristenbelasting','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('totaalborg','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('longstay','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('totaalont','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('contanten','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('debit','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('credit','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('totaalnaar','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('kasverschil','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('stripeguesty','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('stripeinvoice','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('bank','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('airbnb','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('omzet','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\KasboekHotel'
        ));
    }
}
