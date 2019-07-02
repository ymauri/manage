<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KasboekOutType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pinccv','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('creditcards','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('rekening','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('voorverkoop','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('kadopagina','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('tickets','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('belevenissen','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('cash','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('inkoopfood','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('exinkoopfood','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('btwinkoopfood','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('bedrijfskleding','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('exbedrijfskleding','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('btwbedrijfskleding','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('kleineinv','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('exkleineinv','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('btwkleineinv','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('was','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('exwas','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('btwwas','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('bankkosten','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('entertainment','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('exentertainment','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('btwentertainment','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('diversekosten','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('exdiversekosten','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('btwdiversekosten','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('totalout','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('extotalout','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('btwtotalout','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
            ->add('saldo','number', array( 'attr'=>array('tabindex'=>-1,'readonly' =>true,), 'precision' => 2))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\KasboekOut'
        ));
    }
}
