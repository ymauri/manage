<?php

namespace Manage\RestaurantBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Manage\RestaurantBundle\Entity\Status;
use Manage\RestaurantBundle\Entity\Listing;
use Manage\RestaurantBundle\Repository\FolderRepository;
use Manage\RestaurantBundle\Entity\Folder;
use Manage\RestaurantBundle\Entity\Tag;
use Manage\RestaurantBundle\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FurnitureType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       // $tags = Mana TagRepository();
        $builder
            ->add('details', 'textarea', array('required' => false))
            ->add('name', 'text', array('required' => true))
            ->add('serialnumber', 'text', array('required' => true))
            ->add('status')
            ->add('folder', 'entity', array(
                'class' => 'Manage\RestaurantBundle\Entity\Folder',
                'query_builder' => function(FolderRepository $repository) {
                    return $repository->createQueryBuilder('f')
                        ->select('f')
                        ->addOrderBy('f.details');
                },
                'required' => false,
            ))

            ->add('quantity', 'number', array('required' => false))
            ->add('price','number', array('required' => false))
            ->add('totalvalue', 'number', array('required' => false))
            ->add('tags',EntityType::class, array(
                'class' => 'RestaurantBundle:Tag',
                'query_builder' => function (TagRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'required' => false,
                'multiple' => true
            ))
            ->add('image', 'file')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manage\RestaurantBundle\Entity\Furniture'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'restaurantbundle_furniture';
    }
}
