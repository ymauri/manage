<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Manage\RestaurantBundle\Repository\ListingRepository;

class CleaningExtraType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('begin', 'date', array(
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy',
            'attr' => array(
                'class' => 'form-control datepicker',
                'data-provide' => 'datepicker',
                'readonly' => true,
                'data-date-format' => 'dd-mm-yyyy',)))

            ->add('end', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array(
                    'class' => 'form-control datepicker',
                    'data-provide' => 'datepicker',
                    'readonly' => true,
                    'data-date-format' => 'dd-mm-yyyy',)))
            ->add("details")
            ->add('listing', 'entity', array(
                'class' => 'Manage\RestaurantBundle\Entity\Listing',
                'query_builder' => function(ListingRepository $repository) {
                    return $repository->createQueryBuilder('b')
                        ->select('b')
                        ->addOrderBy('b.number');
                },
                'required' => true
            ))
            ->add('dayweek', 'choice', array(
                'choices' => array(
                    '0' => 'Sunday  ',
                    '1' => 'Monday  ',
                    '2' => 'Tuesday  ',
                    '3' => 'Wednesday  ',
                    '4' => 'Thursday  ',
                    '5' => 'Friday   ',
                    '6' => 'Saturday   '
                ),
                'required'    => true,
                'expanded' => true,
                'multiple' => true,
            ))
            ->add('paymentDay', 'choice', array(
                'choices' => $this->getPaymentDays(),
            ))
            ->add('paymentAmount', 'number', array(
                'required' => false
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\CleaningExtra'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'restaurantbundle_cleaningextra';
    }
    
    private function getPaymentDays() {
        $days = [0=>""];
        foreach (range(1, 31) as $item) {
            $days[$item] = $item;
        }
        return $days;
    }
}
