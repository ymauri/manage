<?php
namespace Manage\RestaurantBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('username', null)
            ->add('email')
            ->add('password', 'password', array(
                'required' => false
            ))
            ->add('roles', 'choice', array(
                'choices' => array(
                    'ROLE_CLEANING' => 'Cleaning',
                    'ROLE_MAINTENANCE' => 'Maintenance',
                    'ROLE_SERVICE' => 'Service',
                    'ROLE_RECEPTION' => 'Reception',
                    'ROLE_SUPER_ADMIN' => 'Admin',
                ),
                'required' => true,
                'multiple' => true,
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\User'
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'restaurantbundle_user';
    }
}
