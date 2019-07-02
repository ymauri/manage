<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotNull;

class CardType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maestro', 'number')
            ->add('alipay', 'number')
            ->add('visa', 'number')
            ->add('visaelec', 'number')
            ->add('mastercard', 'number')
            ->add('american', 'number')
            ->add('union', 'number')
            ->add('diners', 'number')
            ->add('vpay', 'number')
            ->add('ccmaestro', 'number')
            //->add('ccalipay', 'number')
            ->add('ccvisa', 'number')
            ->add('ccvisaelec', 'number')
            ->add('ccmastercard', 'number')
            ->add('ccamerican', 'number')
            ->add('ccunion', 'number')
            ->add('ccdiners', 'number')
            ->add('ccvpay', 'number')
            ->add('total', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
            ))
            ->add('totalcc', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
            ))
            ->add('tcredit', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
            ))
            ->add('tdebit', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
            ))
            ->add('profit', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),
            ))
            ->add('iscc', 'choice', array(
                'choices' => array(
                    '1' => 'Ja',
                    '0' => 'Nee'
                ),
                'required'    => true,
                'expanded' => true,
                'multiple' => false,
            ))
            ->add('exmaestro', 'number')
            ->add('exvisa', 'number')
            ->add('exvisaelec', 'number')
            ->add('exmastercard', 'number')
            ->add('examerican', 'number')
            ->add('exunion', 'number')
            ->add('exdiners', 'number')
            ->add('exvpay', 'number')
            ->add('extotal', 'number', array(
                'attr'=>array('tabindex'=>-1,'readonly' =>true,),))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\Card'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'adminbundle_card';
    }
}
