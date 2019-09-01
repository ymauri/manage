<?php

namespace Manage\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller {

    /**
     * Website main page.
     *
     * @Route("/", name="home")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $user=$this->get('security.token_storage')->getToken()->getUser();

        if ( $user->getRole() == 'ROLE_BASIC') {
            return $this->redirect($this->generateUrl('cleaning', array('date' => date('d-m-Y'))));
        }
        if ( $user->getRole() == 'ROLE_REPORTER') {
            return $this->redirect($this->generateUrl('reportissue_index'));
        }

        $em = $this->getDoctrine()->getManager();
        $recepties = $em->getRepository('RestaurantBundle:Reception')->findBy(array('updated'=>new\DateTime()));
        $hotels = $em->getRepository('RestaurantBundle:Hotel')->findBy(array('updated'=>new\DateTime()));
        $skybars = $em->getRepository('RestaurantBundle:Skybar')->findBy(array('updated'=>new\DateTime()));
        $services = $em->getRepository('RestaurantBundle:CashClosure')->findBy(array('updated'=>new\DateTime()));
        $logs = $em->getRepository('RestaurantBundle:Log')->findBy(array('updated'=>new\DateTime()));
        $turnovers = $em->getRepository('RestaurantBundle:Turnover')->findBy(array('updated'=>new\DateTime()));
        $kasboeks = $em->getRepository('RestaurantBundle:Kasboek')->findBy(array('updated'=>new\DateTime()));
        $kasboekshotel = $em->getRepository('RestaurantBundle:KasboekHotel')->findBy(array('updated'=>new\DateTime()));
        return $this->render('AdminBundle:Default:index.html.twig', array(
            'recepties' => $recepties,
            'hotels'    => $hotels,
            'skybars' => $skybars,
            'service' => $services,
            'logs' => $logs,
            'turnovers' => $turnovers,
            'kasboeks' => $kasboeks,
            'kasboekshotel' => $kasboekshotel,
        ));


        /*if ( $user->getRole() == 'ROLE_RECEPTION') {
            return $this->redirect($this->generateUrl('reception', array('date' => date('m-Y'))));
        }

        if ( $user->getRole() == 'ROLE_RESTAURANT') {
            return $this->redirect($this->generateUrl('cashclosure', array('date' => date('m-Y'))));
        }

        if ( $user->getRole() == 'ROLE_HOTEL') {
            return $this->redirect($this->generateUrl('hotel', array('date' => date('m-Y'))));
        }

        if ( $user->getRole() == 'ROLE_SKYBAR') {
            return $this->redirect($this->generateUrl('skybar', array('date' => date('m-Y'))));
        }*/
    }

    /**
     * Website login.
     *
     * @Route("/login", name="login")
     */
    public function loginAction() {
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->render('AdminBundle:Default:login.html.twig', array(
                    'email' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
        ));
    }

    /**
     * Website login.
     *
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction() {
        // The security layer will intercept this request
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction() {
        //close session
    }

    /**
     * 
     * @Route("/parameters/", name="parameters")
     * 
     * @Template()
     */
    public function parametersAction() {
        $em = $this->getDoctrine()->getManager();
        return $this->render('AdminBundle:Default:parameters.html.twig', array(
            'iva'       => $em->getRepository('AdminBundle:Parameters')->getFieldsIva(),
            'hotel'     => $em->getRepository('AdminBundle:Parameters')->getFieldsHotel(),
            'general'   => $em->getRepository('AdminBundle:Parameters')->getFieldsGeneral(),
            'rules'   => $em->getRepository('AdminBundle:Parameters')->getFieldsRules(),
        ));
    }

    /**
     *
     * @Route("/parameters/{type}/", name="parameters_update")
     *
     * @Template()
     */
    public function parametersUpdateAction(Request $request, $type) {
        $em = $this->getDoctrine()->getManager();
        if ($type == 'iva'){
            //actulaizar los datos de parameters
        }
        if ($type == 'hotel'){
            $hotel = $em->getRepository('AdminBundle:Parameters')->getFieldsHotel();
            foreach ($hotel as $item){
                if ($item->getVariable() == 'parking_quantity')
                    $item->setValue((integer)$request->get($item->getVariable()));
                $item->setValue($request->get($item->getVariable()));
            }
            //$request->get('turism-taxes');
        }
        
        if ($type == 'general'){
            $general = $em->getRepository('AdminBundle:Parameters')->getFieldsGeneral();
            foreach ($general as $item){
                $item->setValue($request->get($item->getVariable()));
            }
            //$request->get('turism-taxes');
        }

        if ($type == 'rules'){
            $general = $em->getRepository('AdminBundle:Parameters')->getFieldsRules();
            foreach ($general as $item){
                $item->setValue($request->get($item->getVariable()));
            }
            //$request->get('turism-taxes');
        }
        $em->flush();
        $this->addFlash('success', 'Success! Settings has been saved.');
        return $this->redirect($this->generateUrl('parameters'));
    }
    
    
}
