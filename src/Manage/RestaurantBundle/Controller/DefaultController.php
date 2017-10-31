<?php

namespace Manage\RestaurantBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
     /**
     *      *
     * @Route("/index", name="index_restaurant")
     * 
     *
     */
    public function indexAction()
    {
        return $this->render('RestaurantBundle:Default:index.html.twig', array('name' => 'yolanda'));
    }
}
