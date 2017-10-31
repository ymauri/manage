<?php

namespace Manage\RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Manage\RestaurantBundle\Entity\CashClosure;
use Manage\RestaurantBundle\Entity\CashClosureTotal;
use Manage\RestaurantBundle\Entity\CashClosureBeginBill;
use Manage\RestaurantBundle\Entity\CashClosureBill;

use Manage\RestaurantBundle\Form\CashClosureType;
use Manage\RestaurantBundle\Form\CashClosureTotalType;
use Manage\RestaurantBundle\Form\CashClosureBeginBillType;
use Manage\RestaurantBundle\Form\CashClosureBillType;

/**
 * CashClosure controller.
 *
 * @Route("/cashclosure")
 */
class CashClosureController extends Controller{
    /**
     * Displays a form to create a new CashClosure entity.
     *
     * @Route("/new", name="cashclosure_new")
     * @Template()
     */
    public function newAction()
    {
        $request = $this->getRequest();
        
        $entity_basic = new CashClosure();    
        $form_basic = $this->createForm(new CashClosureType(), $entity_basic);
        
        $entity_total = new CashClosureTotal();
        $form_total = $this->createForm(new CashClosureTotalType(), $entity_total);
        
        $entity_begin = new CashClosureBeginBill();
        $form_begin = $this->createForm(new CashClosureBeginBillType(), $entity_begin);
        
        $entity_bill = new CashClosureBill();
        $form_bill = $this->createForm(new CashClosureBillType(), $entity_bill);
        
        if ($request->getMethod() == 'POST') {
            $form_basic->handleRequest($request);
            $form_total->handleRequest($request);
            $form_begin->handleRequest($request);
            $form_bill->handleRequest($request);
            
            if ($form_basic->isValid() 
                && $form_total->isValid() 
                && $form_begin->isValid()
                && $form_bill->isValid()) {   
                
                $total = $this->validateFormTotal($entity_total);
                $t = $this->getDoctrine()->getEntityManager();
                $t->persist($total);
                $t->flush();

                $begin = $this->validateFormBegin($entity_begin);
                $be = $this->getDoctrine()->getEntityManager();
                $be->persist($begin);
                $be->flush(); 
                
                $bill = $this->validateFormBill($entity_bill);
                $b = $this->getDoctrine()->getEntityManager();
                $b->persist($bill);
                $b->flush(); 
        
                $basic = $this->validateFormBasic($entity_basic, $entity_total, $entity_bill);
                $g = $this->getDoctrine()->getEntityManager();                              
                $g->persist($basic);
                $g->flush();
            }
            
            return $this->redirect('index');
        }
        
        return $this->render('RestaurantBundle:CashClosure:new.html.twig', array(
            'form_basic'    => $form_basic->createView(),
            'form_total'    => $form_total->createView(),
            'form_begin'     => $form_begin->createView(),
            'form_bill'     => $form_bill->createView(),
        ));
    }
    
    private function validateFormBasic($entity_basic, $entity_total, $entity_bill){        
        if (is_null($entity_basic->getName())){
            $entity_basic->setName('Cash Closure Restaurant');
        }
        if (is_null($entity_basic->getDetails())){
            $entity_basic->setDetails('Cash Closure Restaurant');
        }
        if (is_null($entity_basic->getDetails())){
            $entity_basic->setHelp('Cash Closure Restaurant');
        }
        
        $entity_basic->setTotal($entity_total);
        $entity_basic->setBill($entity_bill);
        return $entity_basic;
    }
    
    private function validateFormTotal($entity_total) {
        $xtotal = $entity_total->getXlaag()
                + $entity_total->getXkitchen()
                + $entity_total->getXhoog()
                + $entity_total->getXparking()
                + $entity_total->getXentry()
                + $entity_total->getXspacesrent()
                + $entity_total->getXothers();
        $entity_total->setXtotal($xtotal);
                
        $ztotal = $entity_total->getZlaag()
                + $entity_total->getZkitchen()
                + $entity_total->getZhoog()
                + $entity_total->getZparking()
                + $entity_total->getZentry()
                + $entity_total->getZspacesrent()
                + $entity_total->getZothers();
        $entity_total->setZtotal($ztotal);
        
        if ($entity_total->getSuitesapart()){
            $suites = $entity_total->getLaagdag()
                + $entity_total->getLaagavond()
                + $entity_total->getHoogdag()
                + $entity_total->getHoogavond();
            $entity_total->setSuites($suites);
        }
        else{
            $entity_total->setLaagdag(0);
            $entity_total->setLaagavond(0);
            $entity_total->setHoogdag(0);
            $entity_total->setHoogavond(0);
            $entity_total->setSuites(0);
        }
        return $entity_total;
    }
    
    private function validateFormBill($entity_bill){
        $bills = $entity_bill->getE500() * 500
               + $entity_bill->getE200() * 200
               + $entity_bill->getE100() * 100
               + $entity_bill->getE50()  * 50
               + $entity_bill->getE20()  * 20
               + $entity_bill->getE10()  * 10
               + $entity_bill->getE5()   * 5;
        $entity_bill->setEind($bills);
        $coins = $entity_bill->getE2()   * 2
               + $entity_bill->getE1()  
               + $entity_bill->getE050() * 0.50
               + $entity_bill->getE020() * 0.20
               + $entity_bill->getE010() * 0.10
               + $entity_bill->getE005() * 0.05;
        $entity_bill->setWaarvan($coins);
        return $entity_bill;
    }
    
    private function validateFormBegin($entity_begin){
//        $bills = $entity_bill->getE500() * 500
//               + $entity_bill->getE200() * 200
//               + $entity_bill->getE100() * 100
//               + $entity_bill->getE50()  * 50
//               + $entity_bill->getE20()  * 20
//               + $entity_bill->getE10()  * 10
//               + $entity_bill->getE5()   * 5;
//        $entity_bill->setEind($bills);
//        $coins = $entity_bill->getE2()   * 2
//               + $entity_bill->getE1()  
//               + $entity_bill->getE050() * 0.50
//               + $entity_bill->getE020() * 0.20
//               + $entity_bill->getE010() * 0.10
//               + $entity_bill->getE005() * 0.05;
//        $entity_bill->setWaarvan($coins);
        return $entity_begin;
    }
    
    private function defaultValuesBegin() {
        return array(
            
        );
    }
    
    private function defaultValuesBasic() {
        return array(
           'dated' => new \DateTime('today'),
        );
    }
    /**
     * Lists all CashClosure entities.
     *
     * @Route("/index", name="cashclosure")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('RestaurantBundle:CashClosure')->findAll();

        return $this->render('RestaurantBundle:CashClosure:index.html.twig', array(
           'entities' => $entities,
        ));
    }
    
     /**
     * Lists all CashClosure entities.
     *
     * @Route("/show/{id}", name="cashclosure_show")
     * @Template()
     */
    public function showAction()
    {
        
    }
    
     /**
     * Lists all CashClosure entities.
     *
     * @Route("/edit/{id}", name="cashclosure_edit")
     * @Template()
     */
    public function editAction()
    {
    }

//put your code here
}
