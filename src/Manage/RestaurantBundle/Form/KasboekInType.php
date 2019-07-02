<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KasboekInType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('omzkitchen','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzlaag','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzhoog','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzspacerent','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzvoucher','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzentry','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzparking','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzothers','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omztotalin','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzexkitchen','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzexlaag','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzexhoog','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzexspacerent','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzexvoucher','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzexentry','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzexparking','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzexothers','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzextotalin','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzbtwkitchen','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzbtwlaag','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzbtwhoog','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzbtwspacerent','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzbtwvoucher','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzbtwentry','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzbtwparking','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzbtwothers','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
            ->add('omzbtwtotalin','number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
                'precision' => 2
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\KasboekIn'
        ));
    }
}
