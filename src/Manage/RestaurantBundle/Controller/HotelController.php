<?php

namespace Manage\RestaurantBundle\Controller;

use Manage\RestaurantBundle\Entity\CleaningLog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\Hotel;
use Manage\RestaurantBundle\Form\HotelType;
use Manage\RestaurantBundle\Entity\RCheckoutHotel;
use Manage\RestaurantBundle\Entity\RCheckinHotel;
use Manage\RestaurantBundle\Entity\Bill;
use Manage\RestaurantBundle\Entity\Card;
use Manage\RestaurantBundle\Entity\HotelParking;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Manage\AdminBundle\Entity\RNotifierForm;
use Manage\RestaurantBundle\Entity\Checkin;
use Manage\RestaurantBundle\Entity\Checkout;
use Manage\RestaurantBundle\Entity\Cleaning;
use Manage\RestaurantBundle\Controller\Nomenclator;

/**
 * Hotel controller.
 *
 * @Route("/hotel")
 */
class HotelController extends Controller {

    /**
     * Lists all Listing entities.
     *
     * @Route("/date/{date}/", name="hotel")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($date) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_RECEPTION') {

            $partes = explode('-', $date);
            $date = $partes[1] . '-' . $partes[0];

            $em = $this->getDoctrine()->getManager();
            $consulta = $em->createQuery('SELECT r FROM RestaurantBundle:Hotel r WHERE r.dated >= \'' . $date . '-01\' AND r.dated <= \'' . $date . '-31\' ORDER BY r.dated DESC');
            $entities = $consulta->getResult();
            $consulta = $em->createQuery('SELECT r.form, count(r.id) AS cantidad FROM AdminBundle:RNotifierForm r JOIN r.notifier n WHERE n.form LIKE \'Hotel\' GROUP BY r.form');
            $notifier = $consulta->getResult();
            $result = array();
            foreach ($entities as $entity) {
                foreach ($notifier as $not) {
                    if ($not['form'] == $entity->getId()) {
                        $result[] = $not;
                    }
                }
            }

            return array(
                'entities' => $entities,
                'notifier' => $result,
            );
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }

    /**
     * Displays a form to create a new Listing entity.
     *
     * @Route("/new/", name="hotel_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_RECEPTION') {

            $entity_hotel = new Hotel();
            $bill = new Bill();
            $entity_hotel->setBill($bill);
            $card = new Card();
            $entity_hotel->setCard($card);
            $entity_hotel->setDated(new \DateTime('today'));
            $entity_hotel->setUpdated(new \DateTime('today'));
            $em = $this->getDoctrine()->getManager();
            $tax = $em->getRepository('AdminBundle:Parameters')->findOneBy(array('variable' => 'turism_taxes'));
            $entity_hotel->setTax((float)str_replace(",", ".", $tax->getValue()));
            $parking = $em->getRepository('AdminBundle:Parameters')->findOneBy(array('variable' => 'parking_hotel'));
            $entity_hotel->setParking((float)str_replace(",", ".", $parking->getValue()));
            $limit = $em->getRepository('AdminBundle:Parameters')->findOneBy(array('variable' => 'nights_limit_pay'));
            $entity_hotel->setNightslimit($limit->getValue());
            $amountparking = $em->getRepository('AdminBundle:Parameters')->findOneBy(array('variable' => 'parking_quantity'));
            $entity_hotel->setAmmountparking($amountparking->getValue());
            $em->persist($entity_hotel);
            $em->flush();
            //Creando las relaciones a partir de lo que vino de Guesty
            $checkinguesty = $em->getRepository('RestaurantBundle:Checkin')->findBy(array('date' => new \DateTime('today'), 'status'=>'confirmed'));
            foreach ($checkinguesty as $checkin) {
                $relation = new RCheckinHotel();
                $relation->setHotel($entity_hotel);
                $relation->setListing($em->getRepository('RestaurantBundle:Listing')->findOneBy(array('id' => $checkin->getListing())));
                $relation->setName($checkin->getName());
                $relation->setGuests($checkin->getGuests());
                $relation->setNights($checkin->getNights());
                $relation->setBetalen($checkin->getBetalen());
                $relation->setVoldan($checkin->getVoldan());
                $relation->setSourceguesty($checkin->getSource());
                $relation->setFromguesty(TRUE);
                $relation->setNotes($checkin->getNote());
                $relation->setCheckin($checkin);
                $em->persist($relation);
            }
            $em->flush();
            $checkoutguesty = $em->getRepository('RestaurantBundle:Checkout')->findBy(array('date' => new \DateTime('today'), 'status'=>'confirmed'));
            foreach ($checkoutguesty as $checkout) {
                $relation = new RCheckoutHotel();
                $relation->setHotel($entity_hotel);
                $relation->setListing($em->getRepository('RestaurantBundle:Listing')->findOneBy(array('id' => $checkout->getListing())));
                $relation->setName($checkout->getName());
                $relation->setFromguesty(TRUE);
                $relation->setCheckout($checkout);
                $em->persist($relation);

                $cleaning = $em->getRepository("RestaurantBundle:Cleaning")->findOneBy(array("listing"=>$relation->getListing()->getId(), "dated"=>$relation->getHotel()->getDated()));
                $cleaning = is_null($cleaning) ? new Cleaning() : $cleaning;
                if (is_null($cleaning->getDated())){
                    $cleaning->setStatus(Nomenclator::LISTING_DIRTY);
                    $cleaning->setListing($relation->getListing());
                    $cleaning->setDated($relation->getHotel()->getDated());
                    $cleaning->setIsextra(false);
                    $cleaninglog = new CleaningLog();
                    $cleaninglog->setStatus(Nomenclator::LISTING_DIRTY);
                    $cleaninglog->setUpdatedat(new \DateTime());
                    $cleaninglog->setCleaning($cleaning);
                    $em->persist($cleaninglog);
                }
                $cleaning->setCheckout($relation);
                $em->persist($cleaning);
            }

            /*$hpark = new HotelParking();
            $hpark->setHotel($entity_hotel);
            for ($i = 1; $i <= $amountparking->getValue(); $i++) {
                $hpark->setParkingFalse($i);
                $em->persist($hpark);
            }*/
            $em->flush();
            $blacklist = $this->isBlackList($entity_hotel->getId());
            //if (count($blacklist) > 0){
                //$this->notifyBlacklist($blacklist, $entity_hotel->getId());
            //}

            return $this->redirect($this->generateUrl('hotel_edit', array('id' => $entity_hotel->getId())));
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }
    
    private function notifyBlacklist($blacklist, $id){
        $em = $this->getDoctrine()->getManager();

        //Crear el notificador para este formulario.
       $notifier = $em->getRepository('AdminBundle:Notifier')->findOneBy(array('form'=>'Hotel'));
        $entity = $em->getRepository('RestaurantBundle:Hotel')->findOneBy(array('id'=>$id));
      $mails_array = explode(';',$notifier->getMails());
        $mail_customer = \Swift_Message::newInstance()
            ->setFrom('info@log.towerleisure.nl')
            ->setTo($mails_array)
            ->setSubject("Warning! Black List - Hotel Kassa Cash & Log")
            ->setBody($this->renderView('RestaurantBundle:Hotel:blacklist.html.twig', array(
                'blacklist' => $blacklist,
                'entity_basic' => $entity,
            )))
            ->setContentType("text/html");

        $this->get('mailer')->send($mail_customer);
        /*return $this->render('RestaurantBundle:Hotel:blacklist.html.twig', array(
            'blacklist' => $blacklist,
            'entity_basic' => $entity,
        ));*/
        $mailer = new RNotifierForm();
        $mailer->setNotifier($notifier);
        $mailer->setForm($id);
        $mailer->setBody((string)$mail_customer->getBody());
        $mailer->setDate(new \DateTime('now'));
        $mailer->setSubject("Warning! Black List - Hotel Kassa Cash & Log");
        $mailer->setTo($notifier->getMails());
        //Obtener el status del servidor de correo.
        $mailer->setStatus('Enviado');
        $em->persist($mailer);
        $em->flush();
    }

    /**
     * Displays a form to create a new Listing entity.
     *
     * @Route("/hotelchangedate/{id}/", name="hotel_change_date")
     */
    public function hotelchangedateAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_RECEPTION') {

            $request = $this->getRequest();
            $response = new JsonResponse();
            if ($request->getMethod() == 'POST') {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $entity_hotel = $em->getRepository('RestaurantBundle:Hotel')->findOneBy(array('id' => $id));
                    
                    $entity_hotel->setDated(new \DateTime($request->get('date')));
                    $entity_hotel->setUpdated(new \DateTime('now'));

                    $em->persist($entity_hotel);
                    $em->flush();

                    //Tomar todas las relaciones de Checking y eliminarlas para en cada edición crear las nuevas
                    $checkin_for_remove = $em->getRepository('RestaurantBundle:RCheckinHotel')->findBy(array(
                        'hotel' => $id,
                    ));
                    $checkout_for_remove = $em->getRepository('RestaurantBundle:RCheckoutHotel')->findBy(array(
                        'hotel' => $id,
                    ));

                    foreach ($checkin_for_remove as $in) $em->remove($in);
                    foreach ($checkout_for_remove as $out) $em->remove($out);

                    $em->flush();

                    //Creando las relaciones a partir de lo que vino de Guesty
                    $checkinguesty = $em->getRepository('RestaurantBundle:Checkin')->findBy(array('date' => $entity_hotel->getDated(), 'status'=>'confirmed'));
                    foreach ($checkinguesty as $checkin) {
                        $relation = new RCheckinHotel();
                        $relation->setHotel($entity_hotel);
                        $relation->setListing($em->getRepository('RestaurantBundle:Listing')->findOneBy(array('id' => $checkin->getListing())));
                        $relation->setName($checkin->getName());
                        $relation->setGuests($checkin->getGuests());
                        $relation->setNights($checkin->getNights());
                        $relation->setBetalen($checkin->getBetalen());
                        $relation->setVoldan($checkin->getVoldan());
                        $relation->setSourceguesty($checkin->getSource());
                        $relation->setFromguesty(TRUE);
                        $relation->setNotes($checkin->getNote());
                        $relation->setCheckin($checkin);
                        $em->persist($relation);
                    }

                    $checkoutguesty = $em->getRepository('RestaurantBundle:Checkout')->findBy(array('date' => $entity_hotel->getDated(), 'status'=>'confirmed'));
                    foreach ($checkoutguesty as $checkout) {
                        $relation = new RCheckoutHotel();
                        $relation->setHotel($entity_hotel);
                        $relation->setListing($em->getRepository('RestaurantBundle:Listing')->findOneBy(array('id' => $checkout->getListing())));
                        $relation->setName($checkout->getName());
                        $relation->setFromguesty(TRUE);
                        $relation->setCheckout($checkout);
                        $em->persist($relation);

                        $cleaning = $em->getRepository("RestaurantBundle:Cleaning")->findOneBy(array("listing"=>$relation->getListing(), "dated"=>$relation->getHotel()->getDated()));
                        $cleaning = is_null($cleaning) ? new Cleaning() : $cleaning;
                        if ($cleaning->getDated()){
                            $cleaning->setStatus(Nomenclator::LISTING_DIRTY);
                            $cleaning->setListing($relation->getListing());
                            $cleaning->setDated($relation->getHotel()->getDated());
                            $cleaning->setIsextra(false);
                        }
                        $cleaning->setCheckout($relation);
                        $em->persist($cleaning);

                        $cleaninglog = new CleaningLog();
                        $cleaninglog->setStatus(Nomenclator::LISTING_DIRTY);
                        $cleaninglog->setUpdatedat(new \DateTime());
                        $cleaninglog->setCleaning($cleaning);
                        $em->persist($cleaninglog);
                    }
                    $em->flush();
                    $r = $this->isBlackList($entity_hotel->getId());
                    $response->setData('true');
                    return $response;

                } catch (\Exception $ex) {
                    $response->setData($ex);
                    return $response;
                }
            }
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }

    //Obtener los CheckOuts correspondientes al formulario según la fecha con la que se ha creado
    private function getCheckout() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('RestaurantBundle:Checkout')->findAll();
        $result = array();
        foreach ($entities as $item) {
            $result[] = array(
                "id" => $item->getId(),
                "listing" => $item->getListing(),
//                "checkoutdone"=> $item->getCheckoutdone()
            );
        }
        return $result;
    }

    //Obtener los Checkins correspondientes al formulario
    private function getCheckin() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('RestaurantBundle:Checkin')->findAll();
        $result = array();
        foreach ($entities as $item) {
            $result[] = array(
                "id" => $item->getId(),
                "listing" => $item->getListing(),
//                "checkindone"   => $item->getCheckindone(),
                "nights" => $item->getNights(),
                "guests" => $item->getGuests(),
                "name" => $item->getName(),
            );
        }
        return $result;
    }

    //Obtener los fuentes de reservas activas
    private function getActiveSources() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('RestaurantBundle:Source')->findBy(array(
            'isactive' => TRUE
        ));
        $result = array();
        foreach ($entities as $item) {
            $result[] = array(
                "id" => $item->getId(),
                "details" => $item->getDetails(),
                "guesty" => $item->getGuesty(),
                "extrafield" => $item->getExtrafield()
            );
        }
        return $result;
    }

    //Obtener los apartamentos disponibles para las reservas 
    private function getActiveListing() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('RestaurantBundle:Listing')->findBy(array('activeforrent'=>1));
        $result = array();
        foreach ($entities as $item) {
            $result[] = array(
                "id" => $item->getId(),
                "details" => $item->getNumber(),
            );
        }
        return $result;
    }

    private function getUsers() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AdminBundle:Worker')->getRecepties();
        $result = array();
        foreach ($entities as $item) {
            $result[] = array(
                "id" => $item->getId(),
                "name" => $item->getName(),
            );
        }
        return $result;
    }

    /**
     * Finds and displays a Listing entity.
     *
     * @Route("/{id}/", name="hotel_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_RECEPTION') {

            $em = $this->getDoctrine()->getManager();
            $entity_basic = $em->getRepository('RestaurantBundle:Hotel')->find($id);
            if (!$entity_basic) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
            }
            $canceled = $em->getRepository('RestaurantBundle:Checkin')->findBy(array('date' => $entity_basic->getDated(), 'status'=>'canceled'));
            $help = $em->getRepository('RestaurantBundle:Help')->findBy(array('form'=>'hotel'));
            $contenidos = array();
            foreach ($help as $content){
                $contenidos[$content->getField()] = array(
                    'label' => $content->getLabel(),
                    'content' => $content->getContent(),
                );
            }
            return $this->render('RestaurantBundle:Hotel:edit.html.twig', array(
                'entity_basic' => $entity_basic,
                'rcheckin' => $em->getRepository('RestaurantBundle:RCheckinHotel')->getOrderedCheckin($id),
                'rcheckout' => $em->getRepository('RestaurantBundle:RCheckoutHotel')->getOrderedCheckout($id),
                'users' => $this->getUsers(),
                "cleaning"=> $em->getRepository("RestaurantBundle:Cleaning")->findBy(array('dated'=>$entity_basic->getDated())),
                //'checkout_guesty' => $this->getCheckout(),
                //'checkin_guesty' => $this->getCheckin(),
                'sources' => $this->getActiveSources(),
                'listing' => $this->getActiveListing(),
                'parking' => $em->getRepository('RestaurantBundle:HotelParking')->findOneBy(array('hotel' => $entity_basic->getId())),
                'canceled'=> $canceled,
                'help'=>$contenidos,
                'show' => TRUE
            ));
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }

    /**
     * Displays a form to edit an existing Listing entity.
     *
     * @Route("/{id}/edit/", name="hotel_edit")
     * @Template()
     */
    public function editAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_RECEPTION') {
            $request = $this->getRequest();
            $em = $this->getDoctrine()->getManager();
            $entity_basic = $em->getRepository('RestaurantBundle:Hotel')->find($id);
            $user = $this->get('security.token_storage')->getToken()->getUser();

            if (!$entity_basic) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
            }
            $now = new \DateTime('now');
            //echo  $entity_basic->getUpdated()->diff($now)->d ; die;
            //if (strtotime($olddate->format('d-m-Y')) > strtotime($entity_basic->getUpdated()->format('d-m-Y')) && $user->getRole() != 'ROLE_SUPERADMIN') {
            if (($entity_basic->getUpdated()->diff($now)->d >= 2) && ($user->getRole() != 'ROLE_SUPERADMIN')) {
            //if (($olddate->format('d-m-Y') == $entity_basic->getUpdated()->format('d-m-Y') && ($now->format('G') >= 8) || $entity_basic->getUpdated()->format('d-m-Y') != $now->format('d-m-Y') ) && ($user->getRole() != 'ROLE_SUPERADMIN' && $user->getRole() != 'ROLE_RECEPTION')) {
                $this->addFlash('error', 'Error! This form can not be modified.');
                return $this->redirect($this->generateUrl('hotel', array('date' => date('m-Y'))));
            }

            $entity_basic->setUpdated(new \DateTime('today'));
            $result = $this->isBlackList($id);
            if ($request->getMethod() == 'POST') {
               $data = $request->get('data');
                $final = $request->get('final');
                $response = new JsonResponse();
                $visitedin = $visitedout = array();
                $checkins = $em->getRepository("RestaurantBundle:RCheckinHotel")->findBy(array('hotel' =>$entity_basic->getId()));
                $checkouts = $em->getRepository("RestaurantBundle:RCheckoutHotel")->findBy(array('hotel' =>$entity_basic->getId()));
                foreach ($data as $key => $value) {
                    switch ($key) {
                        case 'form-basic-hotel':
                            $entity_basic = $this->updateHotel($entity_basic, $value);
                            break;
                        case 'form-total-hotel':
                            $entity_basic = $this->updateHotel($entity_basic, $value);
                            break;
                        case 'form-notifier-hotel':
                            $entity_basic = $this->updateHotel($entity_basic, $value);
                            break;
                        case 'form-parking-hotel':
                            $this->updateParkingHotel($entity_basic, $value);
                            break;
                        case 'form-bills':
                            $entity_basic = $this->updateBill($entity_basic, $value);
                            break;
                        case 'form-card':
                            $entity_basic = $this->updateCard($entity_basic, $value);
                            //die;
                            break;
                        case 'form-final':
                            $entity_basic = $this->updateFinal($entity_basic, $value);
                            break;
                        default:
                            if (substr($key, 0, 12) == 'form-checkin') {
                                $visitedin[] = $this->createCheckin($entity_basic, $value);
                            }
                            if (substr($key, 0, 13) == 'form-checkout') {
                                $visitedout[] = $this->createCheckout($entity_basic, $value);
                            }
                            break;
                    }
                }


                try {
                    //Verificar si hay algún checkin que ha sido eliminado ()
                    //if (count($visitedin) < count($checkins)){
                        foreach ($checkins as $c){
                            if (!in_array($c->getId(), $visitedin)){
                                $em->remove($c);
                            }
                        }
                    //}
                    //Verificar si hay algún checkout que ha sido eliminado ()
                    //if (count($visitedout) < count($checkouts)){
                        foreach ($checkouts as $c){
                            if (!in_array($c->getId(), $visitedout)){
                                $em->remove($c);
                            }
                        }
                    //}
                    $em->flush();
                    $entity_basic->setUpdated(new \DateTime('today'));
                    $r = $this->isBlackList($entity_basic->getId());
                    $em->flush();
                    $this->updateCalculos($entity_basic);
                    if ($final == 'true') {
                        if ($user->getRole() == 'ROLE_SUPERADMIN')
                            $entity_basic->setName('true');
                        $entity_basic->setFinished(new \DateTime('today'));
                        $em->flush();
                        $this->sendMail($id);
                    }
                    $cleaning = array();
                    $datacleaning = $em->getRepository("RestaurantBundle:Cleaning")->findBy(array('dated'=>$entity_basic->getDated()));
                    foreach ($datacleaning as $clean){
                        $cleaning[$clean->getListing()->getNumber()] = $clean->getStatus();
                    }
                    $response->setData($cleaning);
                    return $response;

                } catch (\Exception $ex) {
                    $response->setData($ex);
                    return $response;
                }
                //return $response;

            }
            else {
                //Obtener los Departamentos cancelados sennalando los que deben ser cobrados
                //Condicion de cobro: Cancelados con menos de 7 dias de diferencia de la reserva
                $canceled = $em->getRepository('RestaurantBundle:Checkin')->findBy(array('date' => $entity_basic->getDated(), 'status'=>'canceled'));
                $help = $em->getRepository('RestaurantBundle:Help')->findBy(array('form'=>'hotel'));
                $contenidos = array();
                foreach ($help as $content){
                    $contenidos[$content->getField()] = array(
                        'label' => $content->getLabel(),
                        'content' => $content->getContent(),
                    );
                }
                return $this->render('RestaurantBundle:Hotel:edit.html.twig', array(
                    'entity_basic' => $entity_basic,
                    'rcheckin' => $em->getRepository('RestaurantBundle:RCheckinHotel')->getOrderedCheckin($id),
                    'rcheckout' => $em->getRepository('RestaurantBundle:RCheckoutHotel')->getOrderedCheckout($id),
                    "cleaning"=> $em->getRepository("RestaurantBundle:Cleaning")->findBy(array('dated'=>$entity_basic->getDated())),
                    'users' => $this->getUsers(),
                    //'checkout_guesty' => $this->getCheckout(),
                    //'checkin_guesty' => $this->getCheckin(),
                    'sources' => $this->getActiveSources(),
                    'listing' => $this->getActiveListing(),
                    'parking' => $em->getRepository('RestaurantBundle:HotelParking')->findOneBy(array('hotel' => $entity_basic->getId())),
                    'canceled' => $canceled,
                    'help'=>$contenidos,
                    'show' => FALSE
                ));
            }
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }

    //Actualizar Parqueos
    private function updateParkingHotel($entity_basic, $data){
        $em = $this->getDoctrine()->getManager();
        $hotelparking = $em->getRepository('RestaurantBundle:HotelParking')->findOneBy(array('hotel'=>$entity_basic->getId()));
        foreach ($data as $item){
            $upper = strtoupper(substr($item['name'], 0, 1));
            $rest = substr($item['name'], 1);
            $set_method = 'set'.$upper.$rest;
            $hotelparking->$set_method($item['value']);
        }
        $em->persist($hotelparking);
        $em->flush();
    }

    //Actualizar el objeto Hotel
    private function updateHotel($entity_basic, $data) {
        $em = $this->getDoctrine()->getManager();
        try {
            foreach ($data as $value) {
                switch ($value['name']) {
                    case 'dated':
                        if ($entity_basic->getFinished() == null){
                            $entity_basic->setDated(new \DateTime($value['value']));
                        }
                        break;
                    case 'totalover':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $entity_basic->setTotalover($current);
                        break;
                    case 'totalvoldan':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $entity_basic->setTotalvoldan($current);
                        break;
                    case 'totaltoer':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $entity_basic->setTotaltoer($current);
                        break;
                    case 'totalborg':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $entity_basic->setTotalborg($current);
                        break;
                    case 'totalretourborg':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $entity_basic->setTotalretourborg($current);
                        break;
                    case 'totalparking':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $entity_basic->setTotalparking($current);
                        break;
                    case 'totalextra':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $entity_basic->setTotalextra($current);
                        break;
                    case 'totaldag':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $entity_basic->setTotaldag($current);
                        break;
                    case 'userdoor':
                        $user = $em->getRepository('AdminBundle:Worker')->find($value['value']);
                        $entity_basic->setUserdoor($user);
                        break;
                    case 'notify':
                        $entity_basic->setNotify($value['value']);
                        break;
                    default:
                        break;
                }
            }
            $em->flush();
            return $entity_basic;
        } catch (\Exception $ex) {
            return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
        }
    }

    //Crear las relaciones entre el del checkin y el formulario
    private function createCheckin($entity_basic, $data) {
        $em = $this->getDoctrine()->getManager();
        $relation = new RCheckinHotel();
        $relation->setHotel($entity_basic);
        if (isset($data[0]['name']) && $data[0]['name'] == 'id' && $data[0]['value'] > 0) {
            $relation = $em->getRepository('RestaurantBundle:RCheckinHotel')->find($data[0]['value']);
        }
        else {
            foreach ($data as $value) {
                if ($value['name'] == 'listing') {
                    $checkin = $em->getRepository('RestaurantBundle:RCheckinHotel')->findOneBy(array('hotel'=>$entity_basic->getId(), 'listing'=>$value['value']));
                    if (!is_null($checkin) && is_null($checkin->getCheckin()) ){
                        $relation = $checkin;
                    }
                }
            }
        }
        try {
            foreach ($data as $value) {
                switch ($value['name']) {
                    case 'listing':
                        $listing = $em->find('RestaurantBundle:Listing',$value['value']);
                        $relation->setListing($listing);
                        break;
                    case 'checkinid':
                        $checkin = $em->find('RestaurantBundle:Checkin',$value['value']);
                        $relation->setCheckin($checkin);
                        break;
                    case 'name':
                        $relation->setName($value['value']);
                        break;
                    case 'details':
                        $relation->setDetails($value['value']);
                        break;
                    case 'source':
                        $source = $em->getRepository('RestaurantBundle:Source')->find($value['value']);
                        $relation->setSource($source);
                        break;
                    case 'betalen':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $relation->setBetalen($current);
                        break;
                    case 'totalbetalen':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $relation->setTotalbetalen($current);
                        break;
                    case 'voldan':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $relation->setVoldan($current);
                        break;
                    case 'nights':
                        $relation->setNights($value['value']);
                        break;
                    case 'guests':
                        $relation->setGuests($value['value']);
                        break;
                    case 'parkingdag':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $relation->setParkingdag($current);
                        break;
                    case 'parking':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $relation->setParking($current);
                        break;
                    case 'latecheckin':
                        $relation->setLatecheckin($value['value']);
                        break;
                    case 'details':
                        $relation->setDetails($value['value']);
                        break;
                    case 'toer':
                        $relation->setToer($value['value']);
                        break;
                    case 'confirm-checkin':
                        $relation->setCheckindone(1);
                        break;
                    case 'fromguesty':
                        $relation->setFromguesty(1);
                        break;
                    case 'borg':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $relation->setBorg($current);
                        break;
                    /*case 'sourceguesty':
                        $relation->setSourceguesty($value['value']);
                        break;*/
                    case 'readytoclear':
                        $relation->setReadytoclear($value['value']);
                        break;
                    case 'timeforcheck':
                        if ($value['value'] != "")
                            $relation->setTimeforcheck(new \DateTime($value['value']));
                        break;
                    case 'notes':
                        $relation->setNotes($value['value']);
                        break;
                    default:
                        break;
                }
            }
            if (is_null($relation->getCheckindone())){
                $relation->setCheckindone(0);
            }
            if (is_null($relation->getFromguesty())){
                $relation->setFromguesty(0);
            }

            if (!is_null($relation->getListing())){
                $em->persist($relation);
            }
            $em->flush();
            return $relation->getId();
            
        } catch (\Exception $ex) {
            return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
        }
    }
    //Crear las relaciones entre el del checkout y el formulario
    private function createCheckout($entity_basic, $data) {
        $em = $this->getDoctrine()->getManager();
        $relation = new RCheckoutHotel();
        $relation->setHotel($entity_basic);
        if (isset($data[0]['name']) && $data[0]['name'] == 'id' && $data[0]['value'] > 0) {
            $relation = $em->getRepository('RestaurantBundle:RCheckoutHotel')->find($data[0]['value']);
        }
        else {
            foreach ($data as $value) {
                if ($value['name'] == 'listing') {
                    $checkout = $em->getRepository('RestaurantBundle:RCheckoutHotel')->findOneBy(array('hotel'=>$entity_basic->getId(), 'listing'=>$value['value']));
                    if (!is_null($checkout) && is_null($checkout->getCheckin()) ){
                        $relation = $checkout;
                    }
                }
            }
        }
        try {
            $confirm = false;
            foreach ($data as $current){
                if (substr($current['name'], 0, 7) == 'listing'){
                    if ($current['value'] == '') return;
                    $listing = $em->getRepository('RestaurantBundle:Listing')->find($current['value']);
                    $relation->setListing($listing);
                }
                if (substr($current['name'], 0, 7) == 'details'){
                    $relation->setDetails($current['value']);
                }
                if (substr($current['name'], 0, 4) == 'borg'){
                    $relation->setBorg($current['value']);
                }
                if (substr($current['name'], 0, 7) == 'confirm') {
                    $confirm = true;
                }
                if ($current['name'] == 'fromguesty'){
                    $relation->setFromguesty($current['value']);
                }
                if ($current['name'] == 'checkoutid'){
                    $checkout = $em->getRepository('RestaurantBundle:Checkout')->find($current['value']);
                    $relation->setCheckout($checkout);
                }
                if ($current['name'] == 'name'){
                    $relation->setName($current['value']);
                }
            }
            $checkoutdoneold = $relation->getCheckoutdone();
            $relation->setCheckoutdone($confirm);
            $em->persist($relation);
            $cleaning = $em->getRepository("RestaurantBundle:Cleaning")->findOneBy(array("dated"=>$relation->getDate(), "listing"=>$relation->getListing()));
            if (is_null($cleaning)){
                $cleaning = new Cleaning();
                $cleaning->setIsextra(false);
                $cleaning->setListing($relation->getListing());
                $cleaning->setDated($entity_basic->getDated());
                $cleaning->setStatus(Nomenclator::LISTING_DIRTY);
            }
            $cleaning->setCheckout($relation);
            $statusold = $cleaning->getStatus();
            if (!is_null($cleaning->getStatus()) && $cleaning->getStatus() != Nomenclator::LISTING_CLEAN && $cleaning->getStatus() != Nomenclator::LISTING_WORKING)
                $cleaning->setStatus($confirm ? Nomenclator::LISTING_CHECKEDOUT : Nomenclator::LISTING_DIRTY);
            $em->persist($cleaning);
            if ($statusold != $cleaning->getStatus() ){
                $cleaninglog = new CleaningLog();
                $cleaninglog->setStatus($cleaning->getStatus());
                $cleaninglog->setCleaning($cleaning);
                $cleaninglog->setUpdatedat(new \DateTime());
                $em->persist($cleaninglog);
            }
            $em->flush();

            return $relation->getId();
        } catch (\Exception $ex) {
            return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
        }
    }

    //Actualizar los datos del objeto Bill
    private function updateBill($entity_basic, $data){
        $em = $this->getDoctrine()->getManager();
        $bill = $entity_basic->getBill();
        //var_dump($data);die;
        try {
            foreach ($data as $value) {
                $upper = strtoupper(substr($value['name'], 0, 1));
                $rest = substr($value['name'], 1);
                $set_method = 'set'.$upper.$rest;
                //if ($value['value'] > 0){
                    if ($value['name'] == 'eind') {

                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $bill->$set_method($current);
                   }
                    else {
                        $current = str_replace('.', '', $value['value']);
                        $bill->$set_method($current);
                    }
                //}
            }
            $entity_basic->setBill($bill);

            $em->flush();

            return $entity_basic;
        } catch (\Exception $ex) {
            return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
        }
    }
    
    //Actualizar los datos del objeto Card
    private function updateCard($entity_basic, $data){
        $em = $this->getDoctrine()->getManager();
        $card = $entity_basic->getCard();
        //echo print_r(data); die;
        try {
            $ctrl = FALSE;
            foreach ($data as $value) {
                if ($value['name'] == 'iscc') $ctrl = TRUE;
                $upper = strtoupper(substr($value['name'], 0, 1));
                $rest = substr($value['name'], 1);
                $set_method = 'set'.$upper.$rest;
                $current = str_replace('.', '', $value['value']);
                $current = str_replace(',', '.', $current);
                $card->$set_method($current);
            }
            if (!$ctrl) $card->setIscc(FALSE);
            //$em->flush();
            //var_dump($card);die;
            $entity_basic->setCard($card);
            $em->flush();

            return $entity_basic;
        } catch (\Exception $ex) {
            return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
        }
    }
    
    //Actualizar los datos del formulario final
    private function updateFinal($entity_basic, $data){
        $em = $this->getDoctrine()->getManager();
        try {
            foreach ($data as $value) {
                $upper = strtoupper(substr($value['name'], 0, 1));
                $rest = substr($value['name'], 1);
                $set_method = 'set'.$upper.$rest;
                $current = $value['value'];
                if ($value['name'] != 'details') {
                    $a = str_replace('.', '', $value['value']);
                    $current = str_replace(',', '.', $a);
                    //echo $current.'<br/>';
                }
                $entity_basic->$set_method($current);
            }
            $em->flush();
            return $entity_basic;
        } catch (\Exception $ex) {
            return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
        }
    }

    //Actualizar los totales del formulario ultima pagina
    public function updateCalculos($entity){
        $em = $this->getDoctrine()->getManager();
        //Eind Float
        $total = 0;
        $bill = $entity->getBill();
        $total += $bill->getE500() * 500;
        $total += $bill->getE200() * 200;
        $total += $bill->getE100() * 100;
        $total += $bill->getE50() * 50;
        $total += $bill->getE20() * 20;
        $total += $bill->getE10() * 10;
        $total += $bill->getE5() * 5;
        $total += $bill->getE2() * 2;
        $total += $bill->getE1();
        $total += $bill->getE050() * 0.50;
        $total += $bill->getE020() * 0.20;
        $total += $bill->getE010() * 0.10;
        $bill->setEind($total);
        $em->persist($bill);
        $em->flush();

        //Pin Maestro
        $pin = $entity->getCard();
        $debit = $pin->getMaestro() + $pin->getVpay();
        $credit = $pin->getVisa() + $pin->getVisaelec() + $pin->getMastercard() + $pin->getAmerican() + $pin->getUnion() + $pin->getDiners();
        //echo $debit;die;
        $pin->setTdebit($debit);
        $pin->setTcredit($credit);
        $pin->setTotal($debit + $credit);

        if ($pin->getIscc()){
            $debit = $pin->getCcmaestro() + $pin->getCcvpay();
            $credit = $pin->getCcvisa() + $pin->getCcvisaelec() + $pin->getCcmastercard() + $pin->getCcamerican()+ $pin->getCcunion() + $pin->getCcdiners();
            $pin->setCctdebit($debit);
            $pin->setCctcredit($credit);
            $pin->setTotalcc($debit + $credit);
        }
        $em->persist($pin);
        $em->flush();
        $relations = $em->getRepository('RestaurantBundle:RCheckinHotel')->findBy(array("hotel"=>$entity->getId()));
        $totalover = 0;
        $totalvoldan = 0;
        $totaltoer = 0;
        $totalborg = 0;
        $totalparking = 0;
        $totalextra = 0;
        $dago = 0;

        foreach ($relations as $checkin){
            $totalover += $checkin->getBetalen();
            $totalvoldan += $checkin->getVoldan();
            $current = str_replace('.', '', $checkin->getToer());
            $current = str_replace(',', '.', $current);
            $totaltoer += $current;
            $totalborg += $checkin->getBorg();
            $totalparking += $checkin->getParking();
            $totalextra += $checkin->getLatecheckin();
            $dago += (double) $checkin->getTotalbetalen();
        }

        $entity->setTotalover($totalover);
        $entity->setTotalvoldan($totalvoldan);
        $entity->setTotaltoer($totaltoer);
        $entity->setSaldoborg($totalborg);
        $entity->setTotalparking($totalparking);
        $entity->setTotalparkingextra($totalextra);
        $entity->setTotaldag($dago - $totalborg);


        $entity->setTotalont($bill->getEind() + $pin->getTotal() + $pin->getTotalcc() + $pin->getAlipay());
        $entity->setTotalparkingextra($totalextra + $totalover - $totalvoldan);
        $entity->setTotalcontanten($bill->getEind());
        if ($pin->getIscc()) {
            $entity->getTotaldebit($pin->getTdebit() + $pin->getCctdebit());
            $entity->getTotalcredit($pin->getTcredit() + $pin->getCctcredit());
        }
        else {
            $entity->getTotaldebit($pin->getTdebit());
            $entity->getTotalcredit($pin->getTcredit());
        }
        $entity->setKasver($entity->getTotalont() - $entity->getTotaldag() - $totalborg);
        $em->persist($entity);
        $em->flush();
    }

    /**
     * Deletes a Listing entity.
     *
     * @Route("/{id}/delete/", name="hotel_delete")
     * @Method("GET")
     */
    public function deleteAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_RECEPTION') {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:Hotel')->find($id);
            $user = $this->get('security.token_storage')->getToken()->getUser();

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }
            $now = new \DateTime('now');
            //echo  $entity_basic->getUpdated()->diff($now)->d ; die;
            //if (strtotime($olddate->format('d-m-Y')) > strtotime($entity_basic->getUpdated()->format('d-m-Y')) && $user->getRole() != 'ROLE_SUPERADMIN') {
            if (($entity->getUpdated()->diff($now)->d >= 2) && ($user->getRole() != 'ROLE_SUPERADMIN')) {
                $this->addFlash('error', 'Error! This form can not be removed.');
                return $this->redirect($this->generateUrl('hotel', array('date' => date('m-Y'))));
            }
            $rcheckin = $em->getRepository('RestaurantBundle:RCheckinHotel')->findBy(array('hotel' => $id));
            $rcheckout = $em->getRepository('RestaurantBundle:RCheckoutHotel')->findBy(array('hotel' => $id));
            foreach ($rcheckout as $value) {
                $cleaning = $em->getRepository("RestaurantBundle:Cleaning")->findOneBy(array("checkout"=>$value->getCheckout()->getId()));
                if (!is_null($cleaning)){
                    $cleaning->setCheckout(NULL);
                    $em->persist($cleaning);
                }

            }
            $em->flush();
            foreach ($rcheckin as $value) {
                $em->remove($value);
            }

            foreach ($rcheckout as $value) {
                $em->remove($value);
            }
            /*$amountparking = $em->getRepository('RestaurantBundle:HotelParking')->findBy(array('hotel' => $id));
            foreach ($amountparking as $value)
            $em->remove($value);*/
            try{
                $em->remove($entity);
                $em->flush();
                $this->addFlash('success', 'Success! The form has been removed.');
                return $this->redirect($this->generateUrl('hotel', array('date' => date('m-Y'))));


        } catch (\Exception $ex) {
            return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
        }
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');
    }

    private function sendMail($id){
        $em = $this->getDoctrine()->getManager();

        //Crear el notificador para este formulario.
        $notifier = $em->getRepository('AdminBundle:Notifier')->findOneBy(array('form'=>'Hotel'));
        $entity_basic = $em->getRepository('RestaurantBundle:Hotel')->findOneBy(array('id'=>$id));
        $mails_array = explode(';',$notifier->getMails());
        $mail_customer = \Swift_Message::newInstance()
            ->setFrom('info@log.towerleisure.nl')
            ->setTo($mails_array)
            ->setSubject("Hotel Kassa Cash & Log")
            ->setBody($this->renderView('RestaurantBundle:Hotel:mail.html.twig', array(
                'entity_basic' => $entity_basic,
                'rcheckin' => $em->getRepository('RestaurantBundle:RCheckinHotel')->getOrderedCheckin($id),
                'rcheckout' => $em->getRepository('RestaurantBundle:RCheckoutHotel')->getOrderedCheckout($id),
                'users' => $this->getUsers(),
                //'checkout_guesty' => $this->getCheckout(),
                //'checkin_guesty' => $this->getCheckin(),
                'sources' => $this->getActiveSources(),
                'listing' => $this->getActiveListing(),
                )))
            ->setContentType("text/html");

        $this->get('mailer')->send($mail_customer);
        $mailer = new RNotifierForm();
        $mailer->setNotifier($notifier);
        $mailer->setForm($id);
        $mailer->setBody((string)$mail_customer->getBody());
        $mailer->setDate(new \DateTime('now'));
        $mailer->setSubject("Hotel Kassa Cash & Log");
        $mailer->setTo($notifier->getMails());
        //Obtener el status del servidor de correo.
        $mailer->setStatus('Enviado');
        $em->persist($mailer);

        // Extra
        if (!is_null($notifier->getExternals())){
            $mails_array = explode(';',$notifier->getExternals());
            $mail_customer = \Swift_Message::newInstance()
                ->setFrom('info@log.towerleisure.nl')
                ->setTo($mails_array)
                ->setSubject("Hotel Kassa Cash & Log")
                ->setBody($this->renderView('RestaurantBundle:Hotel:mail.html.twig', array(
                    'entity_basic' => $entity_basic,
                    'rcheckin' => $em->getRepository('RestaurantBundle:RCheckinHotel')->getOrderedCheckin($id),
                    'rcheckout' => $em->getRepository('RestaurantBundle:RCheckoutHotel')->getOrderedCheckout($id),
                    'users' => $this->getUsers(),
                    //'checkout_guesty' => $this->getCheckout(),
                    //'checkin_guesty' => $this->getCheckin(),
                    'sources' => $this->getActiveSources(),
                    'listing' => $this->getActiveListing(),
                )))
                ->setContentType("text/html");

            $this->get('mailer')->send($mail_customer);
            $mailer = new RNotifierForm();
            $mailer->setNotifier($notifier);
            $mailer->setForm($id);
            $mailer->setBody((string)$mail_customer->getBody());
            $mailer->setDate(new \DateTime('now'));
            $mailer->setSubject("Hotel Kassa Cash & Log");
            $mailer->setTo($notifier->getExternals());
            //Obtener el status del servidor de correo.
            $mailer->setStatus('Enviado');
            $em->persist($mailer);
        }
        $em->flush();
    }

    /**
     * Displays a form to edit an existing ReceptionParking entity.
     *
     * @Route("/{id}/mail/", name="hotel_mail")
     */
    public function mailAction($id){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_RECEPTION') {
            try {
                $this->sendMail($id);
            } catch (\Exception $ex) {
                return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
            }
            $this->addFlash('success', 'Success! The form has been sent.');
            return $this->redirect($this->generateUrl('hotel', array('date' => date('m-Y'))));
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }

    /**
     * Displays a form to edit an existing ReceptionParking entity.
     *
     * @Route("/dinamiccheckin/{id}/", name="hotel_dinamiccheckin")
     * @Method("POST")
     */
    public function dinamicCheckinAction($id){

        $em = $this->getDoctrine()->getManager();
        $lastcheckinhotel = $em->getRepository("RestaurantBundle:RCheckinHotel")->lastCheckin($id);
        $hotel = $em->getRepository("RestaurantBundle:Hotel")->findOneBy(array('id'=>$id));
        $date = (array)$lastcheckinhotel->getCheckin()->getUpdatedat();
        $dated = (array) $hotel->getDated();
        $checkins = $em->getRepository('RestaurantBundle:RCheckinHotel')->getNewerThan($date['date'], $dated['date']);
        //Ya tengo los checkin pendientes .
        $rchecking = array();
        foreach ($checkins as $checkin){
            $relation = new RCheckinHotel();
            $relation->setHotel($hotel);
            $relation->setListing($em->getRepository('RestaurantBundle:Listing')->findOneBy(array('id'=>$checkin->getListing())));
            $relation->setName($checkin->getName());
            $relation->setGuests($checkin->getGuests());
            $relation->setNights($checkin->getNights());
            $relation->setBetalen($checkin->getBetalen());
            $relation->setVoldan($checkin->getVoldan());
            $relation->setSourceguesty($checkin->getSource());
            $relation->setFromguesty(TRUE);
            $relation->setCheckin($checkin);
            $em->persist($relation);
            $rchecking[]= $relation;
        }
        return $this->render('RestaurantBundle:Hotel:dinamiccheckin.html.twig', array(
            'entity_basic' => $hotel,
            "rcheckin" => $rchecking,
            'sources' => $this->getActiveSources(),
            'listing' => $this->getActiveListing(),
        ));

    }

       /**
     *
     * @Route("/lastcheckin/", name="hotel_last_checkin")
     * @Method("POST")
     */
    public function lastCheckinAction(){

            $response = new JsonResponse();
            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();
            $apto = $request->get('apto');
            $hotel_id = $request->get('hotel_id');
            $hotel = $em->getRepository('RestaurantBundle:Hotel')->findOneBy(array('id' => $hotel_id));
            $borg = $em->getRepository('RestaurantBundle:RCheckoutHotel')->getLastCheckinBorg($apto, $hotel);
            $response->setData($borg);
            return $response;

    }
    
    
    
    private function isBlackList($id){
        $em = $this->getDoctrine()->getManager();
        $blacklist = $em->getRepository('RestaurantBundle:BlackList')->findAll();
        $control = array();
        $checkins = $em->getRepository('RestaurantBundle:RCheckinHotel')->findBy(array('hotel'=>$id));
        foreach ($blacklist as $element){
            foreach ($checkins as $checkin){
                $currentis = false;
                if (!is_null($checkin->getCheckin()) && $element->getEmail() == $checkin->getCheckin()->getEmail()) {
                    $checkin->setBlacklist(true);
                    $control[] = $checkin->getCheckin();
                    $currentis = true;
                }
                else if (!is_null($checkin->getCheckin()) && $element->getName() == $checkin->getCheckin()->getName()) {
                    $checkin->setBlacklist(true);
                    $control[] = $checkin->getCheckin();
                    $currentis = true;
                } else if (!is_null($checkin->getCheckin())){
                    $name = explode(" ",$element->getName());
                    $checkinname = explode(" ",$checkin->getCheckin()->getName());

                    if (count($name) == 2 && isset($name[1]) && isset($checkinname[1]) && $name[1] == $checkinname[1]){
                        $currentis = true;
                    }
                    else if (count($name) == 3 && isset($name[1]) && isset($checkinname[1]) && $name[1] == $checkinname[1] && isset($name[2]) && isset($checkinname[2]) && $name[2] == $checkinname[2]){
                        $currentis = true;
                    }

                }
                if ($currentis) $checkin->setBlacklist(true);
                else $checkin->setBlacklist(false);
            }
        }
        $em->flush();
        return $control;
    }
    
    

    
}
