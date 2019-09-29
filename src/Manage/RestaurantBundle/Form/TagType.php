<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Manage\RestaurantBundle\Entity\Status;
use Manage\RestaurantBundle\Entity\Listing;
use Manage\RestaurantBundle\Entity\FolderRepository;
use Manage\RestaurantBundle\Entity\Folder;
use Manage\RestaurantBundle\Entity\Tag;
use Manage\RestaurantBundle\Entity\TagRepository;

class TagType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       // $tags = Mana TagRepository();
        $builder
            ->add('name')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\Tag'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'restaurantbundle_tag';
    }
}
