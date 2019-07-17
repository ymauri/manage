<?php

namespace Manage\RestaurantBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Manage\AdminBundle\Entity\Status;
use Manage\RestaurantBundle\Entity\Listing;
use Manage\RestaurantBundle\Entity\ListingRepository;
use Manage\RestaurantBundle\Controller\Nomenclator;


class RuleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('details', 'textarea', array(
                'required' => true
            ))
            ->add('name', 'text', array(
                'required' => true
            ))
            ->add('time', 'time', array(
                'widget' => 'single_text',
                'required' => false
            ))
            ->add('cond', 'choice', array(
                //'choices'  => Nomenclator::RULE_CONDITION,
                'choices'  => array(
                    'listing_available_more'        => 'More than X listings availables ',
                    'listing_available_less'        => 'Less than X listings availables ',
                    'none_condition'                => 'No Condition',
                ),
                'required' => true
            ))
            ->add('conditionvalue','number', array(
                'required' => false
            ))
            ->add('action', 'choice', array(
                //'choices'  => Nomenclator::RULE_ACTION,
                'choices'  => array(
                    'listing_lower_price'   => 'Lower Price',
                    'listing_raise_price'   => 'Raise Price',
                    'listing_change_price'   => 'Change Price',
                ),
                'required' => true
            ))
            ->add('actionvalue','number', array(
                'required' => true
            ))
            ->add('unit', 'choice', array(
                'choices'  => array(
                    'percentage'        => 'Percentage (%)',
                    'euro'        => 'Euros (€)'
                ),
                'required' => true
            ))
            ->add('method', 'choice', array(
                //'choices'  => Nomenclator::RULE_GOAL,
                'choices'  => array(
                    'changepricenow'        => 'Modify Listing Price'
                ),
                'required' => true
            ))
            ->add('active', 'checkbox', array(
                'attr' => array(
                    'class' => 'make-switch',
                    'data-size' => 'small',)))
            ->add('begin', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array(
                    'class' => 'form-control',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy',),
                'required' => false
            ))
            ->add('ends', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array(
                    'class' => 'form-control',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy',),
                'required' => false
            ))
            ->add('daysahead','choice', array(
                'choices'  => $this->daysaheadoptions(),
                'required' => true,
            ))
            ->add('typeofapartment', 'choice', array(
                'choices' => array(
                    ''=>'All',
                    //'South' => 'South',
                    'Studio' => 'Studio',
                    //'Sea View' => 'Sea view',
                    //'West' => 'West',
                    'Apartment' => 'Apartment',
                    //'Corner' => 'Corner',
                ),
                'required' => false,
                'multiple'=>true,
            ))
            ->add('apartments', 'choice', array(
                'choices' => array(
                    '58a5dffa3798420400c8e691' => '727',
                    '58a5dffa3798420400c8e6dd' => '728',
                    '58a5dffa3798420400c8e5d7' => '729',
                    '58a5dffb3798420400c8e79f' => '730',
                    '58a5dff93798420400c8e4f2' => '731',
                    '58a5dffb3798420400c8e7ec' => '732',
                    '58a5dffa3798420400c8e4ff' => '733',
                    '58a5dffa3798420400c8e632' => '734',
                    '58a5dff93798420400c8e4bf' => '735',
                    '58a5dffb3798420400c8e815' => '736',

                    '58a5dff93798420400c8e487' => '737',
                    '58a5dffa3798420400c8e608' => '738',
                    '58a5dffa3798420400c8e57e' => '767',
                    '58a5dffa3798420400c8e773' => '768',
                    '58a5dffa3798420400c8e734' => '769',
                    '58a5dffa3798420400c8e715' => '770',
                    '58a5dffa3798420400c8e529' => '771',
                    '58a5dffb3798420400c8e7ca' => '772',
                    '58a5dffa3798420400c8e54f' => '773',
                    '58a5dffa3798420400c8e6c2' => '774',
                ),
                'required' => false,
                'multiple'=>true,
            ))
            /*->add('apartments', 'choice', array(
                'choices' => array(
                    ''=>'All',
                    'South' => 'South',
                    'Studio' => 'Studio',
                    'Sea View' => 'Sea view',
                    'West' => 'West',
                    'Apartment' => 'Apartment',
                    'Corner' => 'Corner',
                ),
                'multiple'=>true,
            ))*/
            ->add('bytype', 'choice', array(
                'choices' => array(
                    '1' => 'By Listing Tag',
                    '0' => 'By Listing Number'
                ),
                'required'    => true,
                'expanded' => true,
                'multiple' => false,
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
            ->add('priority', 'number', array(
                'required'    => true,
            ))
            ->add('ishook', 'choice', array(
                'choices' => array(
                    '0' => 'At a specific date/time',
                    '1' => 'When a guesty event occurs',
                ),
                'required'    => true,
                'expanded' => true,
                'multiple' => false,
            ))
            ->add('startingfrom','choice', array(
                'choices'  => $this->daysstartingptions(),
                'required' => true,
            ))
        ;
    }

    private function daysaheadoptions(){
        $result = array();
        $result[] = 'Today';
        $result[] = 'Tomorrow';
        for($i = 2; $i <= 50; $i++){
            $result[] = $i.' days from now';
        }
        return $result;
    }

    private function daysstartingptions(){
        $result = array();
        $result[] = null;
        for($i = 2; $i <= 365; $i++){
            $result[] = $i.' days';
        }
        return $result;
    }

    private function typeofaptos(){
        $result = array();

    }

    private function getListings(){
        //$repository = ListingRepository();
        //$listings = $repository->getAllActiveListings();
        $arraydata = array();
       /* foreach ($listings as $listing){
            $arraydata[$listing->getIdguesty()] = $listing->getDetails();
        }*/
        return $arraydata;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\Rule'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'restaurantbundle_rule';
    }
}
