<?php

namespace Manage\RestaurantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\Reception;
use Manage\RestaurantBundle\Form\ReceptionType;

/**
 * Reception controller.
 *
 * @Route("/reception")
 */
class ReceptionController extends Controller {
    /**
     * Displays a form to create a new CashClosure entity.
     *
     * @Route("/new", name="reception_new")
     * @Template()
     */
    public function newAction()
    {
        $request = $this->getRequest();
        
        $entity_basic = new Reception();    
        $entity_basic->setGiftvouchers(json_encode($this->getVouchers()));
        $entity_basic->setParking(json_encode($this->getParking()));
        $form_basic = $this->createForm(new ReceptionType(), $entity_basic);
        
        if ($request->getMethod() == 'POST') {
            $form_basic->handleRequest($request);
            if ($form_basic->isValid() ) { 
                $t = $this->getDoctrine()->getEntityManager();
                $t->persist($entity_basic);
                $t->flush();
            }            
            return $this->redirect('index');
        }
        
        return $this->render('RestaurantBundle:Reception:new.html.twig', array(
            'form_basic'    => $form_basic->createView(),
        ));
    }
    
    private function getVouchers() {
        $em=$this->getDoctrine()->getEntityManager();
        $listado = $em->getRepository('RestaurantBundle:ReceptionVoucher')->findBy(array(
            'forreception'=>true));
        $result = array();
        foreach ($listado as $value) {
            $result[] = array(
                'id'        =>  $value->getId(),
                'details'   =>  $value->getDetails(),
                'value'     =>  $value->getValue(),
            );
        }
        return $result;
    }
    
    private function getParking() {
        $em=$this->getDoctrine()->getEntityManager();
        $listado = $em->getRepository('RestaurantBundle:ReceptionParking')->findBy(array());
        $result = array();
        foreach ($listado as $value) {
            $result[] = array(
                'id'        =>  $value->getId(),
                'details'   =>  $value->getDetails(),
                'value'     =>  $value->getValue(),
            );
        }
        return $result;
    }
    
    private function getVouchersValues(){
        
    }
    private function getParkingValues(){
        
    }
    
}
