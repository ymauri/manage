<?php

namespace Manage\RestaurantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\TurnoverReception;
use Manage\RestaurantBundle\Entity\TurnoverService;
use Manage\RestaurantBundle\Entity\TurnoverSkybar;
use Manage\RestaurantBundle\Entity\TurnoverOmzet;
use Manage\RestaurantBundle\Entity\Turnover;
use Manage\RestaurantBundle\Form\TurnoverReceptionType;
use Manage\RestaurantBundle\Form\TurnoverServiceType;
use Manage\RestaurantBundle\Form\TurnoverSkybarType;
use Manage\RestaurantBundle\Form\TurnoverOmzetType;
use Manage\RestaurantBundle\Form\TurnoverType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Manage\AdminBundle\Entity\RNotifierForm;


/**
 * Turnover controller.
 *
 * @Route("/turnover")
 */
class TurnoverController extends Controller {

    /**
     *
     * @Route("/new/", name="turnover_new")
     * @Template()
     */
    public function newAction() {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN'){
            return $this->render('AdminBundle:Exception:error403.html.twig');
        }
        $entity_basic = new Turnover();
        $entity_basic->setDated(new \DateTime('today - 1 day'));
        $entity_basic->setUpdated(new \DateTime());
        
        $em = $this->getDoctrine()->getManager();
        
        $reception = new TurnoverReception();
        $em->persist($reception);
        $service = new TurnoverService();
        $em->persist($service);
        $skybar = new TurnoverSkybar();
        $em->persist($skybar);
        $omzet = new TurnoverOmzet();
        $em->persist($omzet);
        $em->flush();
        
        $entity_basic->setReception($reception);
        $entity_basic->setService($service);
        $entity_basic->setSkybar($skybar);
        $entity_basic->setOmzet($omzet);
        $em->persist($entity_basic);
        $em->flush();
        $entity_basic = $this->updateCalculos($entity_basic);

        return $this->redirect($this->generateUrl('turnover_edit', array('id' => $entity_basic->getId())));
    }

   
    /**
     * Lists all Turnover entities.
     *
     * @Route("/date/{date}/", name="turnover")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($date) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN'){
            return $this->render('AdminBundle:Exception:error403.html.twig');
        }
        $partes = explode('-', $date);
        $date = $partes[1].'-'.$partes[0];

        $em = $this->getDoctrine()->getManager();
        //$entities = $em->getRepository('RestaurantBundle:Turnover')->findBy(array('dated'=>'> 2018-01-01', 'dated'=>'< 2018-01-31'), array('dated'=>'DESC'));
        $consulta = $em->createQuery('SELECT r FROM RestaurantBundle:Turnover r WHERE r.dated >= \''.$date.'-01\' AND r.dated <= \''.$date.'-31\' ORDER BY r.dated DESC');
        $entities = $consulta->getResult();

        $consulta = $em->createQuery('SELECT r.form, count(r.id) AS cantidad FROM AdminBundle:RNotifierForm r JOIN r.notifier n WHERE n.form LIKE \'Turnover\' GROUP BY r.form');
        $notifier = $consulta->getResult();
        //var_dump($notifier);die;
        $result = array();
        foreach ($entities as $entity){
            foreach ($notifier as $not){
                if ($not['form'] == $entity->getId()){
                    $result[] = $not;
                }
            }
        }

        return array(
            'entities' => $entities,
            'notifier' => $result,
        );
    }


    /**
     * Finds and displays a Turnover entity.
     *
     * @Route("/{id}/", name="turnover_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN'){
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
        $em = $this->getDoctrine()->getManager();

        $entity_basic = $em->getRepository('RestaurantBundle:Turnover')->find($id);
        if (!$entity_basic) {
            return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
        }
        $form_basic = $this->createForm(new TurnoverType(), $entity_basic);
        $form_reception = $this->createForm(new TurnoverReceptionType(), $entity_basic->getReception());
        $form_service = $this->createForm(new TurnoverServiceType(), $entity_basic->getService());
        $form_skybar = $this->createForm(new TurnoverSkybarType(), $entity_basic->getSkybar());
        $form_omzet = $this->createForm(new TurnoverOmzetType(), $entity_basic->getOmzet());

        return $this->render('RestaurantBundle:Turnover:edit.html.twig', array(
                        'entity_basic' => $entity_basic,
                        'form_basic' => $form_basic->createView(),
                        'form_service' => $form_service->createView(),
                        'form_reception' => $form_reception->createView(),
                        'form_skybar' => $form_skybar->createView(),
                        'form_omzet' => $form_omzet->createView(),
                        'show'  => TRUE,
            ));
    }

    /**
     * Finds and displays a Turnover entity.
     *
     * @Route("/{id}/delete/", name="turnover_delete")
     * @Template()
     */
    public function deleteAction($id) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN'){
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('RestaurantBundle:Turnover')->find($id);
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if (!$entity) {
            return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
        }
        $now = new \DateTime('now');
        //echo  $entity_basic->getUpdated()->diff($now)->d ; die;
        //if (strtotime($olddate->format('d-m-Y')) > strtotime($entity_basic->getUpdated()->format('d-m-Y')) && $user->getRole() != 'ROLE_SUPERADMIN') {
        if (($entity->getUpdated()->diff($now)->d >= 2) && ($user->getRole() != 'ROLE_SUPERADMIN')) {
            $this->addFlash('error', 'Error! This form can not be removed.');
            return $this->redirect($this->generateUrl('turnover', array('date'=>date('m-Y'))));
        }
        try {
           
            $em->remove($entity);
            $em->flush();
        } catch (\Exception $ex) {
            return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
        }
        $this->addFlash('success', 'Success! The form has been removed.');
        return $this->redirect($this->generateUrl('turnover', array('date'=>date('m-Y'))));
    }

    /**
     * @Route("/{id}/edit/", name="turnover_edit")
     */
    public function editAction($id) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN'){
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $user=$this->get('security.token_storage')->getToken()->getUser();

        $entity_basic = $em->getRepository('RestaurantBundle:Turnover')->find($id);
        if (!$entity_basic) {
            return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
        }
            //{% if 'today - 3 day' | date('d-m-Y') < entity.updated | date('d-m-Y') %}
        $olddate = new \DateTime('today - 1 day');
        $now = new \DateTime('now');
        //if (strtotime($olddate->format('d-m-Y')) > strtotime($entity_basic->getUpdated()->format('d-m-Y')) && $user->getRole() != 'ROLE_SUPERADMIN') {
        //if (($olddate->format('d-m-Y') == $entity_basic->getDated()->format('d-m-Y') && ($now->format('G') >= 8) || $entity_basic->getDated()->format('d-m-Y') != $now->format('d-m-Y') ) && $user->getRole() != 'ROLE_SUPERADMIN') {
        if (($entity_basic->getUpdated()->diff($now)->d >= 2) && ($user->getRole() != 'ROLE_SUPERADMIN')) {
            $this->addFlash('error', 'Error! This form can not be modified.');
            return $this->redirect($this->generateUrl('turnover', array('date'=>date('m-Y'))));
        }
        
        $form_basic = $this->createForm(new TurnoverType(), $entity_basic);
        $form_reception = $this->createForm(new TurnoverReceptionType(), $entity_basic->getReception());
        $form_service = $this->createForm(new TurnoverServiceType(), $entity_basic->getService());
        $form_skybar = $this->createForm(new TurnoverSkybarType(), $entity_basic->getSkybar());
        $form_omzet = $this->createForm(new TurnoverOmzetType(), $entity_basic->getOmzet());
        $response = new JsonResponse();
        if ($request->getMethod() == 'POST') {
            $form_basic->handleRequest($request);
            $form_service->handleRequest($request);
            $form_reception->handleRequest($request);
            $form_skybar->handleRequest($request);
            $form_omzet->handleRequest($request);
            try {
                    $entity_basic = $this->updateCalculos($entity_basic);
                    $entity_basic->setUpdated(new \DateTime());
                   
                    $not = $em->getRepository('AdminBundle:RNotifierForm')->findOneBy(array('form'=>$entity_basic->getId()));
                    if (!is_null($entity_basic->getFinished()) ){
                        $entity_basic->setFinished(new \DateTime());
                        if (is_null($not)) $this->sendMail($id);
                    }
                    $em->persist($entity_basic);
                    $em->flush();

                    $response->setData('true');
                    return $response;

            } catch (\Exception $ex) {
                $response->setData($ex->getMessage());
                return $response;
            }
            return $response;
        } else {
            $entity_basic = $this->updateCalculos($entity_basic);
            return $this->render('RestaurantBundle:Turnover:edit.html.twig', array(
                        'entity_basic' => $entity_basic,
                        'form_basic' => $form_basic->createView(),
                        'form_service' => $form_service->createView(),
                        'form_reception' => $form_reception->createView(),
                        'form_skybar' => $form_skybar->createView(),
                        'form_omzet' => $form_omzet->createView(),
                        'show'  => FALSE,
            ));
        }
    }

    //A partir de la entidad Turnover se realizan todos los cálculos correspondientes
    private function updateCalculos($entity_turnover, $date = null){
        $em = $this->getDoctrine()->getManager();
        $turnover_receptie = $entity_turnover->getReception();
        $turnover_service= $entity_turnover->getService();
        $turnover_skybar = $entity_turnover->getSkybar();
        $turnover_omzet = $entity_turnover->getOmzet();

        $service_fooddag = 0;
        $service_foodavond = 0;
        $service_suites = 0;
        $service_beveragedag = 0;
        $service_beverageavond= 0;

        $skybar_fooddag = 0;
        $skybar_foodavond = 0;
        $skybar_suites = 0;
        $skybar_beveragedag = 0;
        $skybar_beverageavond = 0;

        //RECEPTIE
        $form_receptie = null;
        if ($date != null)
            $form_receptie = $em->getRepository('RestaurantBundle:Reception')->findOneBy(array('dated'=>new \DateTime($date)));
        else
            $form_receptie = $em->getRepository('RestaurantBundle:Reception')->findOneBy(array('dated'=>$entity_turnover->getDated()));
        //var_dump($form_receptie);
        if (!is_null($form_receptie)){

            $completo = $form_receptie->getProfit()+$form_receptie->getParkingtotal()+$form_receptie->getOthersales();
            $turnover_receptie->setOmzvoucher($form_receptie->getProfit() + ($form_receptie->getKasverschil()* ( $form_receptie->getProfit()/$completo)));
            $turnover_receptie->setOmzparking($form_receptie->getParkingtotal() + ($form_receptie->getKasverschil() * ($form_receptie->getParkingtotal()/$completo)));
            $turnover_receptie->setOmzoverig($form_receptie->getOthersales() + ($form_receptie->getKasverschil() * ($form_receptie->getOthersales()/$completo)));
            $turnover_receptie->setOmztotal($turnover_receptie->getOmzvoucher()+$turnover_receptie->getOmzparking()+$turnover_receptie->getOmzoverig());

            $turnover_receptie->setOntdebitcard($form_receptie->getCard()->getTdebit());
            $turnover_receptie->setOntcreditcard($form_receptie->getCard()->getTcredit());
            $turnover_receptie->setOnttotal($form_receptie->getCard()->getTdebit()+$form_receptie->getCard()->getTcredit());
        }
        else {
            $turnover_receptie->setOmzvoucher(0);
            $turnover_receptie->setOmzparking(0);
            $turnover_receptie->setOmzoverig(0);
            $turnover_receptie->setOmztotal(0);

            $turnover_receptie->setOntdebitcard(0);
            $turnover_receptie->setOntcreditcard(0);
            $turnover_receptie->setOnttotal(0);
        }
        $total = null;

        //SERVICE
        if ($date != null)
            $total = $em->getRepository('RestaurantBundle:CashClosure')->findOneBy(array('dated'=>new \DateTime($date)));
        else
            $total = $em->getRepository('RestaurantBundle:CashClosure')->findOneBy(array('dated'=>$entity_turnover->getDated()));
        if (!is_null($total)){
            $form_service = $total->getTotal();
            $turnover_service = $entity_turnover->getService();
            if ($form_service->getZtotal() > 0){
                //die(($form_service->getZkitchen().'/'.$form_service->getZtotal() ).'*'.($total->getCompleteprofit().'+'.$total->getKasverschil()));
                $turnover_service->setOmzkitchen(($form_service->getZkitchen()/$form_service->getZtotal() )*($total->getCompleteprofit()+$total->getKasverschil()));
                $turnover_service->setOmzlaag(($form_service->getZlaag()/$form_service->getZtotal())*($total->getCompleteprofit()+$total->getKasverschil()));
                $turnover_service->setOmzhoog(($form_service->getZhoog()/$form_service->getZtotal())*($total->getCompleteprofit()+$total->getKasverschil()));
                $turnover_service->setOmzentry(($form_service->getZentry()/$form_service->getZtotal())*($total->getCompleteprofit()+$total->getKasverschil()));
                $turnover_service->setOmzparking(($form_service->getZparking()/$form_service->getZtotal())*($total->getCompleteprofit()+$total->getKasverschil()));
                $turnover_service->setOmzspacerent(($form_service->getZspacesrent()/$form_service->getZtotal())*($total->getCompleteprofit()+$total->getKasverschil()));
                $turnover_service->setOmzothers(($form_service->getZothers()/$form_service->getZtotal())*($total->getCompleteprofit()+$total->getKasverschil()));
                $turnover_service->setOmztotal($total->getCompleteprofit()+$total->getKasverschil());
                $turnover_service->setOmzvouchersrek($total->getSkymoneytotal());
            }


            $service_fooddag = $form_service->getXkitchen() /1.06;
            $service_foodavond = ($form_service->getZkitchen() - $form_service->getXkitchen())/1.06;
            $service_suites = ($form_service->getZspacesrent() + $form_service->getZothers())/1.21;
            $service_beveragedag = ($form_service->getXlaag()/1.06) + ($form_service->getXhoog()/1.21);
            $service_beverageavond = (($form_service->getZlaag()-$form_service->getXlaag())/1.06) + (($form_service->getZhoog()-$form_service->getXhoog())/1.21);

            $form_service = $total;
            $turnover_service->setOntdebitcard($form_service->TotalDebitCard());
            $turnover_service->setOntcreditcard($form_service->TotalCreditCard());
            $turnover_service->setOntbelevoucher($form_service->getBelevoucherstotal());
            $turnover_service->setOntvooverkoop($form_service->getVoorverkooptotal());
            $turnover_service->setOntkadopagina($form_service->getKadovoucherstotal());
            $turnover_service->setOntrekening($form_service->getRekeningtotal());
            $turnover_service->setOnttickets($form_service->getTicketvoucherstotal());
            $turnover_service->setOnttotal($turnover_service->getOntdebitcard()+$turnover_service->getOntcreditcard()+$form_service->getBelevoucherstotal()+$form_service->getVoorverkooptotal()+$form_service->getKadovoucherstotal()+$form_service->getRekeningtotal()+$form_service->getTicketvoucherstotal());

            }
        else {
            $turnover_service = $entity_turnover->getService();
            $turnover_service->setOmzkitchen(0);
            $turnover_service->setOmzlaag(0);
            $turnover_service->setOmzhoog(0);
            $turnover_service->setOmzentry(0);
            $turnover_service->setOmzparking(0);
            $turnover_service->setOmzspacerent(0);
            $turnover_service->setOmzothers(0);
            $turnover_service->setOmztotal(0);
            //$turnover_service->setOmzvouchersrek();
            $turnover_service->setOntdebitcard(0);
            $turnover_service->setOntcreditcard(0);
            $turnover_service->setOntbelevoucher(0);
            $turnover_service->setOntvooverkoop(0);
            $turnover_service->setOntkadopagina(0);
            $turnover_service->setOntrekening(0);
            $turnover_service->setOnttickets(0);
            $turnover_service->setOnttotal(0);
        }

        
        //SKYBAR
        if ($date != null)
            $total = $form_skybar = $em->getRepository('RestaurantBundle:Skybar')->findOneBy(array('dated'=>new \DateTime($date)));
        else
            $total = $form_skybar = $em->getRepository('RestaurantBundle:Skybar')->findOneBy(array('dated'=>$entity_turnover->getDated()));
         if (!is_null($total)){
            $form_skybar = $total->getTotal();
            $turnover_skybar = $entity_turnover->getSkybar();
             if ($form_skybar->getZtotal() > 0){
                 $turnover_skybar->setOmzkitchen(($form_skybar->getZkitchen()/$form_skybar->getZtotal() )*($total->getCompleteprofit()+$total->getKasverschil()));
                 $turnover_skybar->setOmzlaag(($form_skybar->getZlaag()/$form_skybar->getZtotal())*($total->getCompleteprofit()+$total->getKasverschil()));
                 $turnover_skybar->setOmzhoog(($form_skybar->getZhoog()/$form_skybar->getZtotal())*($total->getCompleteprofit()+$total->getKasverschil()));
                 $turnover_skybar->setOmzentry(($form_skybar->getZentry()/$form_skybar->getZtotal())*($total->getCompleteprofit()+$total->getKasverschil()));
                 $turnover_skybar->setOmzparking(($form_skybar->getZparking()/$form_skybar->getZtotal())*($total->getCompleteprofit()+$total->getKasverschil()));
                 $turnover_skybar->setOmzspacerent(($form_skybar->getZspacesrent()/$form_skybar->getZtotal())*($total->getCompleteprofit()+$total->getKasverschil()));
                 $turnover_skybar->setOmzothers(($form_skybar->getZothers()/$form_skybar->getZtotal())*($total->getCompleteprofit()+$total->getKasverschil()));
                 $turnover_skybar->setOmztotal($total->getCompleteprofit()+$total->getKasverschil());
                 $turnover_skybar->setOmzvouchersrek($total->getSkymoneytotal());
             }

             $skybar_fooddag = $form_skybar->getXkitchen() /1.06;
             $skybar_foodavond = ($form_skybar->getZkitchen() - $form_skybar->getXkitchen())/1.06;
             $skybar_suites = ($form_skybar->getZspacesrent() + $form_skybar->getZothers())/1.21;
             $skybar_beveragedag = ($form_skybar->getXlaag()/1.06) + ($form_skybar->getXhoog()/1.21);
             $skybar_beverageavond = (($form_skybar->getZlaag()-$form_skybar->getXlaag())/1.06) + (($form_skybar->getZhoog()-$form_skybar->getXhoog())/1.21);

             $form_skybar = $total;
            $turnover_skybar->setOntdebitcard($form_skybar->TotalDebitCard());
            $turnover_skybar->setOntcreditcard($form_skybar->TotalCreditCard());
            $turnover_skybar->setOntbelevoucher($form_skybar->getBelevoucherstotal());
            $turnover_skybar->setOntvooverkoop($form_skybar->getVoorverkooptotal());
            $turnover_skybar->setOntkadopagina($form_skybar->getKadovoucherstotal());
            $turnover_skybar->setOntrekening($form_skybar->getRekeningtotal());
            $turnover_skybar->setOnttickets($form_skybar->getTicketvoucherstotal());
            $turnover_skybar->setOnttotal($turnover_skybar->getOntdebitcard()+$turnover_skybar->getOntcreditcard()+$form_skybar->getBelevoucherstotal()+$form_skybar->getVoorverkooptotal()+$form_skybar->getKadovoucherstotal()+$form_skybar->getRekeningtotal()+$form_skybar->getTicketvoucherstotal());

         }
        else {

            $turnover_skybar = $entity_turnover->getSkybar();
            $turnover_skybar->setOmzkitchen(0);
            $turnover_skybar->setOmzlaag(0);
            $turnover_skybar->setOmzhoog(0);
            $turnover_skybar->setOmzentry(0);
            $turnover_skybar->setOmzparking(0);
            $turnover_skybar->setOmzspacerent(0);
            $turnover_skybar->setOmzothers(0);
            $turnover_skybar->setOmztotal(0);

            $turnover_skybar->setOntdebitcard(0);
            $turnover_skybar->setOntcreditcard(0);
            $turnover_skybar->setOntbelevoucher(0);
            $turnover_skybar->setOntvooverkoop(0);
            $turnover_skybar->setOntkadopagina(0);
            $turnover_skybar->setOntrekening(0);
            $turnover_skybar->setOnttickets(0);
            $turnover_skybar->setOnttotal(0);
        }
        //GENERALES
        $turnover_omzet = $entity_turnover->getOmzet();
        $turnover_omzet->setOmzkitchen($turnover_service->getOmzkitchen()+$turnover_skybar->getOmzkitchen());
        $turnover_omzet->setOmzexkitchen($turnover_omzet->getOmzkitchen()/1.06);
        $turnover_omzet->setOmzlaag($turnover_service->getOmzlaag()+$turnover_skybar->getOmzlaag()+$turnover_receptie->getOmzoverig());
        $turnover_omzet->setOmzexlaag($turnover_omzet->getOmzlaag()/1.06);
        $turnover_omzet->setOmzhoog($turnover_service->getOmzhoog()+$turnover_skybar->getOmzhoog());
        $turnover_omzet->setOmzexhoog($turnover_omzet->getOmzhoog()/1.21);
        $turnover_omzet->setOmzspacerent($turnover_service->getOmzspacerent()+$turnover_skybar->getOmzspacerent());
        $turnover_omzet->setOmzexspacerent($turnover_omzet->getOmzspacerent()/1.21);
        $turnover_omzet->setOmzvouchersrek($turnover_receptie->getOmzvoucher()-($turnover_skybar->getOmzvouchersrek()+$turnover_service->getOmzvouchersrek()));
        $turnover_omzet->setOmzexvouchersrek($turnover_omzet->getOmzvouchersrek()/1.06);
        $turnover_omzet->setOmzentry($turnover_service->getOmzentry()+$turnover_skybar->getOmzentry());
        $turnover_omzet->setOmzexentry($turnover_omzet->getOmzentry()/1.06);
        $turnover_omzet->setOmzparking($turnover_service->getOmzparking()+$turnover_skybar->getOmzparking()+$turnover_receptie->getOmzparking());
        $turnover_omzet->setOmzexparking($turnover_omzet->getOmzparking()/1.21);
        $turnover_omzet->setOmzothers($turnover_service->getOmzothers()+$turnover_skybar->getOmzothers());
        $turnover_omzet->setOmzexothers($turnover_omzet->getOmzothers()/1.21);
        
        $turnover_omzet->setOmztotal(
                $turnover_omzet->getOmzkitchen() +
                $turnover_omzet->getOmzlaag()+
                $turnover_omzet->getOmzhoog() +
                $turnover_omzet->getOmzspacerent() + 
                $turnover_omzet->getOmzvouchersrek() + 
                $turnover_omzet->getOmzentry() + 
                $turnover_omzet->getOmzparking() +
                $turnover_omzet->getOmzothers());
        
        $turnover_omzet->setOmzextotal(
                $turnover_omzet->getOmzexkitchen() +
                $turnover_omzet->getOmzexlaag()+
                $turnover_omzet->getOmzexhoog() +
                $turnover_omzet->getOmzexspacerent() + 
                $turnover_omzet->getOmzexvouchersrek() + 
                $turnover_omzet->getOmzexentry() + 
                $turnover_omzet->getOmzexparking() +
                $turnover_omzet->getOmzexothers());
        
        $entity_turnover->setOntdebitcard($turnover_receptie->getOntdebitcard()+$turnover_service->getOntdebitcard()+$turnover_skybar->getOntdebitcard());
        $entity_turnover->setOntcreditcard($turnover_receptie->getOntcreditcard()+$turnover_service->getOntcreditcard()+$turnover_skybar->getOntcreditcard());
        $entity_turnover->setOntrekening($turnover_service->getOntrekening()+$turnover_skybar->getOntrekening());
        $entity_turnover->setOntvooverkoop($turnover_service->getOntvooverkoop()+$turnover_skybar->getOntvooverkoop());
        $entity_turnover->setOntkadopagina($turnover_service->getOntkadopagina()+$turnover_skybar->getOntkadopagina());
        $entity_turnover->setOnttickets($turnover_service->getOnttickets()+$turnover_skybar->getOnttickets());
        $entity_turnover->setOntbelevoucher ($turnover_service->getOntbelevoucher()+$turnover_skybar->getOntbelevoucher());
        
        $entity_turnover->setReception($turnover_receptie);
        $entity_turnover->setService($turnover_service);
        $entity_turnover->setSkybar($turnover_skybar);
        $entity_turnover->setOmzet($turnover_omzet);
        //var_dump($entity_turnover->getDated());

        //L1nda Omzetten

        $entity_turnover->setOmzkitchendag($service_fooddag + $skybar_fooddag);
        $entity_turnover->setOmzkitchenavond($service_foodavond + $skybar_foodavond);
        $entity_turnover->setOmzbeverageps($service_suites + $skybar_suites + $skybar_beveragedag + $skybar_beverageavond);
        $entity_turnover->setOmzbeveragedag($service_beveragedag);
        $entity_turnover->setOmzbeverageavond($service_beverageavond);

        $em->persist($entity_turnover);
        $em->flush();
        return $entity_turnover; 
    }

    private function sendMail($id){
        $em = $this->getDoctrine()->getManager();
        //Crear el notificador para este formulario.
        $notifier = $em->getRepository('AdminBundle:Notifier')->findOneBy(array('form'=>'Turnover'));
        $entity_basic = $em->getRepository('RestaurantBundle:Turnover')->findOneBy(array('id'=>$id));
        $mails_array = explode(';',$notifier->getMails());
        $mail_customer = \Swift_Message::newInstance()
            ->setFrom('info@log.towerleisure.nl')
            ->setTo($mails_array)
            ->setSubject("Turnover")
            ->setBody($this->renderView('RestaurantBundle:Turnover:mail.html.twig', array(
                'entity_basic' => $entity_basic)))
            ->setContentType("text/html");

        $this->get('mailer')->send($mail_customer);
        $mailer = new RNotifierForm();
        $mailer->setNotifier($notifier);
        $mailer->setForm($id);
        $mailer->setBody((string)$mail_customer->getBody());
        $mailer->setDate(new \DateTime('now'));
        $mailer->setSubject("Turnover");
        $mailer->setTo($notifier->getMails());
        //Obtener el status del servidor de correo.
        $mailer->setStatus('Enviado');
        $em->persist($mailer);
        if (!is_null($notifier->getExternals())){
            $mails_array = explode(';',$notifier->getExternals());
            $mail_customer = \Swift_Message::newInstance()
                ->setFrom('info@log.towerleisure.nl')
                ->setTo($mails_array)
                ->setSubject("Turnover")
                ->setBody('prueba')

                ->setContentType("text/html");

            $this->get('mailer')->send($mail_customer);
            $mailer = new RNotifierForm();
            $mailer->setNotifier($notifier);
            $mailer->setForm($id);
            $mailer->setBody((string)$mail_customer->getBody());
            $mailer->setDate(new \DateTime('now'));
            $mailer->setSubject("Turnover");
            $mailer->setTo($notifier->getExternals());
            //Obtener el status del servidor de correo.
            $mailer->setStatus('Enviado');
            $em->persist($mailer);
        }
        $em->flush();

    }

    /**
     * Displays a form to edit an existing TurnoverParking entity.
     *
     * @Route("/{id}/mail/", name="turnover_mail")
     */
    public function mailAction($id){
        try{
            $this->sendMail($id);
        } catch (\Exception $ex) {
            return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
        }
        $em = $this->getDoctrine()->getManager();
        $entity_basic = $em->getRepository('RestaurantBundle:Turnover')->findOneBy(array('id'=>$id));
        //$date = $entity_basic->getDated()->format('m-Y');
        $this->addFlash('success', 'Success! The form has been sent.');
        return $this->redirect($this->generateUrl('turnover', array('date'=>date('m-Y'))));
    }

    /**
     *
     * @Route("/turnoverchangedate/{id}/", name="turnover_change_date")
     */
    public function turnoverchangedateAction($id) {

        $request = $this->getRequest();
        $response = new JsonResponse();

        try {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:Turnover')->findOneBy(array('id' => $id));

            $entity->setDated(new \DateTime($request->get('date')));
            $entity->setUpdated(new \DateTime('now'));

            $em->persist($entity);
            $em->flush();
            $entity = $this->updateCalculos($entity, $request->get('date'));

            $response->setData('true');
            return $response;

        }catch (\Exception $ex) {
            $response->setData($ex);
            return $response;
        }

    }
}
