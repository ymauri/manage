<?php

namespace Manage\RestaurantBundle\Controller;

use Manage\RestaurantBundle\Entity\CleaningLog;
use Manage\RestaurantBundle\Entity\RCleaningExtraHotel;
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
use Manage\RestaurantBundle\Entity\RNotifierForm;
use Manage\RestaurantBundle\Entity\Checkin;
use Manage\RestaurantBundle\Entity\Checkout;
use Manage\RestaurantBundle\Entity\Cleaning;
use Manage\RestaurantBundle\Controller\Nomenclator;
use Manage\RestaurantBundle\Components\fpdf\FPDF;
use Manage\RestaurantBundle\Components\fpdi\Fpdi;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Hotel controller.
 *
 * @Route("/hotel")
 */
class HotelController extends Controller {

    private $entity_basic;
    private $em;

    /**
     * Lists all Listing entities.
     *
     * @Route("/date/{date}/", name="hotel")
     * @Method("GET")
     * @Security("is_granted('ROLE_HOTEL_FORM')")
     * @Template()
     */
    public function indexAction($date) {

            $partes = explode('-', $date);
            $date = $partes[1] . '-' . $partes[0];

            $em = $this->getDoctrine()->getManager();
        $consulta = $em->createQuery('SELECT r FROM RestaurantBundle:Hotel r WHERE r.dated >= \'' . $date . '-01\' AND r.dated <= \'' . $date . '-31\' ORDER BY r.dated DESC');
            $entities = $consulta->getResult();
            $consulta = $em->createQuery('SELECT r.form, count(r.id) AS cantidad FROM RestaurantBundle:RNotifierForm r JOIN r.notifier n WHERE n.form LIKE \'Hotel\' GROUP BY r.form');
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

    /**
     * Displays a form to create a new Listing entity.
     *
     * @Route("/new/", name="hotel_new")
     * @Method("GET")
     * @Security("is_granted('ROLE_HOTEL_FORM')")
     * @Template()
     */
    public function newAction() {

            $entity_hotel = new Hotel();
            $bill = new Bill();
            $entity_hotel->setBill($bill);
            $card = new Card();
            $entity_hotel->setCard($card);
            $entity_hotel->setDated(new \DateTime('today'));
            $entity_hotel->setUpdated(new \DateTime('today'));
            $em = $this->getDoctrine()->getManager();
            $tax = $em->getRepository('RestaurantBundle:Parameters')->findOneBy(array('variable' => 'turism_taxes'));
            $entity_hotel->setTax((float)str_replace(",", ".", $tax->getValue()));
            $parking = $em->getRepository('RestaurantBundle:Parameters')->findOneBy(array('variable' => 'parking_hotel'));
            $entity_hotel->setParking((float)str_replace(",", ".", $parking->getValue()));
            $limit = $em->getRepository('RestaurantBundle:Parameters')->findOneBy(array('variable' => 'nights_limit_pay'));
            $entity_hotel->setNightslimit((integer)$limit->getValue());
            $amountparking = $em->getRepository('RestaurantBundle:Parameters')->findOneBy(array('variable' => 'parking_quantity'));
            $entity_hotel->setAmmountparking((float)$amountparking->getValue());
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
//            $em->flush();
            $blacklist = $this->isBlackList($entity_hotel->getId());
            //if (count($blacklist) > 0){
                //$this->notifyBlacklist($blacklist, $entity_hotel->getId());
            //}

            //Buscar los long stay pendientes por pagar

        $pending = $em->getRepository('RestaurantBundle:CleaningExtra')->pendingPayment($entity_hotel->getDated());
        foreach ($pending as $item) {
            $cleaning = new RCleaningExtraHotel();
            $cleaning->setHotel($entity_hotel);
            $cleaning->setCleaningextra($item);
            $cleaning->setPaymentamount($item->getPaymentamount());
            $cleaning->setPaymentday($item->getPaymentday());
            $cleaning->setPayed(0);
            $em->persist($cleaning);
        }
        $em->flush();
//            $pending = $em->getRepository('RestaurantBundle:CleaningExtra')->findBy()
            return $this->redirect($this->generateUrl('hotel_edit', array('id' => $entity_hotel->getId())));

    }

    private function notifyBlacklist($blacklist, $id){
        $em = $this->getDoctrine()->getManager();

        //Crear el notificador para este formulario.
       $notifier = $em->getRepository('RestaurantBundle:Notifier')->findOneBy(array('form'=>'Hotel'));
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
     * @Route("/hotelchangedate/{id}/", name="hotel_change_date")
     * @Security("is_granted('ROLE_HOTEL_FORM')")
     */
    public function hotelchangedateAction($id, Request $request) {
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
                        $em->flush();

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
                        $em->flush();

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
                                //Buscar los long stay pendientes por pagar

                    $pending = $em->getRepository('RestaurantBundle:CleaningExtra')->pendingPayment($entity_hotel->getDated());
                    foreach ($pending as $item) {
                        $cleaning = new RCleaningExtraHotel();
                        $cleaning->setHotel($entity_hotel);
                        $cleaning->setCleaningextra($item);
                        $cleaning->setPaymentamount($item->getPaymentamount());
                        $cleaning->setPaymentday($item->getPaymentday());
                        $cleaning->setPayed(0);
                        $em->persist($cleaning);
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
        return $this->render('RestaurantBundle:Exception:error403.html.twig');

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
        $entities = $em->getRepository('RestaurantBundle:Worker')->getRecepties();
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
     * @Security("is_granted('ROLE_HOTEL_FORM')")
     * @Template()
     */
    public function showAction($id) {

            $em = $this->getDoctrine()->getManager();
            $entity_basic = $em->getRepository('RestaurantBundle:Hotel')->find($id);
            if (!$entity_basic) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
            }
            $dbcanceled = $em->getRepository('RestaurantBundle:Checkin')->findBy(array('date' => $entity_basic->getDated(), 'status'=>'canceled'));
            $canceled = array();
            foreach ($dbcanceled as $item){
                if($item->getCanceledat()->diff($item->getTime())->days < 7){
                    $canceled[] = $item;
                }
            }
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
                'show' => TRUE,
                'pendingPayment' => $em->getRepository('RestaurantBundle:RCleaningExtraHotel')->findBy(array('hotel' => $entity_basic->getId()))

            ));
    }

    /**
     * Displays a form to edit an existing Listing entity.
     *
     * @Route("/{id}/edit/", name="hotel_edit")
     * @Security("is_granted('ROLE_HOTEL_FORM')")
     * @Template()
     */
    public function editAction($id) {
            $request = $this->getRequest();
            $this->em = $this->getDoctrine()->getManager();
            $this->entity_basic = $this->em->getRepository('RestaurantBundle:Hotel')->find($id);

            if (!$this->entity_basic) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
            }
            $now = new \DateTime('now');
            if (($this->entity_basic->getUpdated()->diff($now)->d >= 2) && (!$this->isGranted('ROLE_SUPER_ADMIN'))) {
                $this->addFlash('error', 'Error! This form can not be modified.');
                return $this->redirect($this->generateUrl('hotel', array('date' => date('m-Y'))));
            }
            
            $this->entity_basic->setUpdated(new \DateTime('today'));
            if ($request->getMethod() == 'POST') {
               $data = $request->get('data');
                $final = $request->get('final');
                $response = new JsonResponse();
                $visitedin = $visitedout = array();
                $checkins = $this->em->getRepository("RestaurantBundle:RCheckinHotel")->findBy(array('hotel' =>$this->entity_basic->getId()));
                $checkouts = $this->em->getRepository("RestaurantBundle:RCheckoutHotel")->findBy(array('hotel' =>$this->entity_basic->getId()));
                foreach ($data as $key => $value) {
                    switch ($key) {
                        case 'form-basic-hotel':
                            $this->updateHotel($value);
                            break;
                        case 'form-total-hotel':
                            $this->updateHotel($value);
                            break;
                        case 'form-notifier-hotel':
                            $this->updateHotel($value);
                            break;
                        case 'form-bills':
                            $this->updateBill($value);
                            break;
                        case 'form-card':
                            $this->updateCard($value);
                            //die;
                            break;
                        case 'form-final':
                            $this->updateFinal($value);
                            break;
                        default:
                            if (substr($key, 0, 12) == 'form-checkin') {
                                $visitedin[] = $this->createCheckin($value);
                            }
                            if (substr($key, 0, 13) == 'form-checkout') {
                                $visitedout[] = $this->createCheckout($value);
                            }
                            if (substr($key, 0, 12) == 'form-payment') {
                                $em = $this->getDoctrine()->getEntityManager();
                                $long = $em->getRepository('RestaurantBundle:RCleaningExtraHotel')->find($value[0]['value']);
                                if (isset($value[1])) {
                                     $long->setPayed(1);
                                    $long->setPayedat(new \DateTime());
                                } else {
                                    $long->setPayed(0);
                                    $long->setPayedat(null);
                                }
                                $em->flush();

                            }
                            break;
                    }
                    $this->em->flush();
                }
                try {
                    //Verificar si hay algún checkin que ha sido eliminado ()
                    //if (count($visitedin) < count($checkins)){
                        foreach ($checkins as $c){
                            if (!in_array($c->getId(), $visitedin)){
                                $this->em->remove($c);
                            }
                        }
                    //}
                    //Verificar si hay algún checkout que ha sido eliminado ()
                    //if (count($visitedout) < count($checkouts)){
                        foreach ($checkouts as $c){
                            if (!in_array($c->getId(), $visitedout)){
                                $this->em->remove($c);
                            }
                        }
                    //}
                    $this->em->flush();
                    $this->entity_basic->setUpdated(new \DateTime('today'));
                    //$r = $this->isBlackList($this->entity_basic->getId());
                    $this->em->persist($this->entity_basic);
                    $this->em->flush();
                    $this->updateCalculos();
                    if ($final == 'true') {
                        if ($this->isGranted('ROLE_SUPER_ADMIN'))
                            $this->entity_basic->setName('true');
                        $this->entity_basic->setFinished(new \DateTime('today'));
                        $this->em->persist($this->entity_basic);
                        $this->em->flush();
                        $this->sendMail($id);
                    }
                    $cleaning = array();
                    $datacleaning = $this->em->getRepository("RestaurantBundle:Cleaning")->findBy(array('dated'=>$this->entity_basic->getDated()));
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
                $canceled = $this->em->getRepository('RestaurantBundle:Checkin')->findBy(array('date' => $this->entity_basic->getDated(), 'status'=>'canceled'));
                $arr_canceled = array();
                foreach ($canceled as $item) {
                    if ($item->getCanceledat()->diff($item->getTime())->days < 7){
                        $arr_canceled[] = $item;
                    }
                }
                $help = $this->em->getRepository('RestaurantBundle:Help')->findBy(array('form'=>'hotel'));
                $contenidos = array();
                foreach ($help as $content){
                    $contenidos[$content->getField()] = array(
                        'label' => $content->getLabel(),
                        'content' => $content->getContent(),
                    );
                }
                return $this->render('RestaurantBundle:Hotel:edit.html.twig', array(
                    'entity_basic' => $this->entity_basic,
                    'rcheckin' => $this->em->getRepository('RestaurantBundle:RCheckinHotel')->getOrderedCheckin($id),
                    'rcheckout' => $this->em->getRepository('RestaurantBundle:RCheckoutHotel')->getOrderedCheckout($id),
                    "cleaning"=> $this->em->getRepository("RestaurantBundle:Cleaning")->findBy(array('dated'=>$this->entity_basic->getDated())),
                    'users' => $this->getUsers(),
                    //'checkout_guesty' => $this->getCheckout(),
                    //'checkin_guesty' => $this->getCheckin(),
                    'sources' => $this->getActiveSources(),
                    'listing' => $this->getActiveListing(),
                    //'parking' => $em->getRepository('RestaurantBundle:HotelParking')->findOneBy(array('hotel' => $this->entity_basic->getId())),
                    'canceled' => $arr_canceled,
                    'help'=>$contenidos,
                    'show' => FALSE,
                    'pendingPayment' => $this->em->getRepository('RestaurantBundle:RCleaningExtraHotel')->findBy(array('hotel' => $this->entity_basic->getId()))

            ));
            }
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
    private function updateHotel($data) {

            foreach ($data as $value) {
                switch ($value['name']) {
                    case 'dated':
                        if ($this->entity_basic->getFinished() == null){
                            $this->entity_basic->setDated(new \DateTime($value['value']));
                        }
                        break;
                    case 'totalover':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $this->entity_basic->setTotalover($current);
                        break;
                    case 'totalvoldan':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $this->entity_basic->setTotalvoldan($current);
                        break;
                    case 'totaltoer':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $this->entity_basic->setTotaltoer($current);
                        break;
                    case 'totalborg':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $this->entity_basic->setTotalborg($current);
                        break;
                    case 'totalretourborg':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $this->entity_basic->setTotalretourborg($current);
                        break;
                    case 'totalparking':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $this->entity_basic->setTotalparking($current);
                        break;
                    case 'totalextra':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $this->entity_basic->setTotalextra($current);
                        break;
                    case 'totaldag':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $this->entity_basic->setTotaldag($current);
                        break;
                    case 'userdoor':
                        $user = $this->em->getRepository('RestaurantBundle:Worker')->find($value['value']);
                        $this->entity_basic->setUserdoor($user);
                        break;
                    case 'notify':
                        $this->entity_basic->setNotify($value['value']);
                        break;
                    default:
                        break;
                }
            }
            //$this->em->flush();
            //return $entity_basic;
    }

    //Crear las relaciones entre el del checkin y el formulario
    private function createCheckin($data) {
        //$em = $this->getDoctrine()->getManager();
        $relation = new RCheckinHotel();
        $relation->setHotel($this->entity_basic);
        if (isset($data[0]['name']) && $data[0]['name'] == 'id' && $data[0]['value'] > 0) {
            $relation = $this->em->getRepository('RestaurantBundle:RCheckinHotel')->find($data[0]['value']);
        }
        else {
//            foreach ($data as $value) {
//                if ($value['name'] == 'listing') {
//                    $checkin = $this->em->getRepository('RestaurantBundle:RCheckinHotel')->findOneBy(array('hotel'=>$this->entity_basic->getId(), 'listing'=>$value['value']));
//                    if (!is_null($checkin) && is_null($checkin->getCheckin()) ){
//                        $relation = $checkin;
//                    }
//                }
//            }
        }
        try {
            foreach ($data as $value) {
                switch ($value['name']) {
                    case 'listing':
                        $listing = $this->em->find('RestaurantBundle:Listing',$value['value']);
                        $relation->setListing($listing);
                        break;
                    case 'checkinid':
                        $checkin = $this->em->find('RestaurantBundle:Checkin',$value['value']);
                        $relation->setCheckin($checkin);
                        break;
                    case 'name':
                        $relation->setName($value['value']);
                        break;
                    case 'details':
                        $relation->setDetails($value['value']);
                        break;
                    case 'source':
                        $source = $this->em->getRepository('RestaurantBundle:Source')->find($value['value']);
                        $relation->setSource($source);
                        break;
                    case 'betalen':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $relation->setBetalen((float)$current);
                        break;
                    case 'totalbetalen':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $relation->setTotalbetalen((float)$current);
                        break;
                    case 'voldan':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $relation->setVoldan((float)$current);
                        break;
                    case 'nights':
                        $relation->setNights((integer)$value['value']);
                        break;
                    case 'guests':
                        $relation->setGuests((integer)$value['value']);
                        break;
                    case 'parkingdag':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $relation->setParkingdag((float)$current);
                        break;
                    case 'parking':
                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $relation->setParking((float)$current);
                        break;
                    case 'latecheckin':
                        $relation->setLatecheckin((float)$value['value']);
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
                        $relation->setBorg((float)$current);
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

            //if (!is_null($relation->getListing())){
              //  $this->em->persist($relation);
            //}
            $this->em->persist($relation);

            return $relation->getId();

        } catch (\Exception $ex) {
            return $this->render('RestaurantBundle:Exception:exception.html.twig', array('message' => $ex));
        }
    }
    //Crear las relaciones entre el del checkout y el formulario
    private function createCheckout($data) {
        //$em = $this->getDoctrine()->getManager();
        $relation = new RCheckoutHotel();
        $relation->setHotel($this->entity_basic);
        if (isset($data[0]['name']) && $data[0]['name'] == 'id' && $data[0]['value'] > 0) {
            $relation = $this->em->getRepository('RestaurantBundle:RCheckoutHotel')->find($data[0]['value']);
        }
        else {
//            foreach ($data as $value) {
//                if ($value['name'] == 'listing') {
//                    $checkout = $this->em->getRepository('RestaurantBundle:RCheckoutHotel')->findOneBy(array('hotel'=>$this->entity_basic->getId(), 'listing'=>$value['value']));
//                    if (!is_null($checkout) && is_null($checkout->getCheckin()) ){
//                        $relation = $checkout;
//                    }
//                }
//            }
        }
        try {
            $confirm = false;
            foreach ($data as $current){
                if (substr($current['name'], 0, 7) == 'listing'){
                    if ($current['value'] == '') return;
                    $listing = $this->em->getRepository('RestaurantBundle:Listing')->find($current['value']);
                    $relation->setListing($listing);
                }
                if (substr($current['name'], 0, 7) == 'details'){
                    $relation->setDetails($current['value']);
                }
                if (substr($current['name'], 0, 4) == 'borg'){
                    $relation->setBorg((float)$current['value']);
                }
                if (substr($current['name'], 0, 7) == 'confirm') {
                    $confirm = true;
                }
                if ($current['name'] == 'fromguesty'){
                    $relation->setFromguesty((integer)$current['value']);
                }
                if ($current['name'] == 'checkoutid'){
                    $checkout = $this->em->getRepository('RestaurantBundle:Checkout')->find($current['value']);
                    $relation->setCheckout($checkout);
                }
                if (substr($current['name'], 0, 4) == 'name'){
                    $relation->setName($current['value']);
                }
            }
            $checkoutdoneold = $relation->getCheckoutdone();
            $relation->setCheckoutdone($confirm);
            $this->em->persist($relation);
            $cleaning = $this->em->getRepository("RestaurantBundle:Cleaning")->findOneBy(array("dated"=>$relation->getDate(), "listing"=>$relation->getListing()));
            if (is_null($cleaning)){
                $cleaning = new Cleaning();
                $cleaning->setIsextra(false);
                $cleaning->setListing($relation->getListing());
                $cleaning->setDated($this->entity_basic->getDated());
                $cleaning->setStatus(Nomenclator::LISTING_DIRTY);
            }
            $cleaning->setCheckout($relation);
            $statusold = $cleaning->getStatus();
            if (!is_null($cleaning->getStatus()) && $cleaning->getStatus() != Nomenclator::LISTING_CLEAN && $cleaning->getStatus() != Nomenclator::LISTING_WORKING)
                $cleaning->setStatus($confirm ? Nomenclator::LISTING_CHECKEDOUT : Nomenclator::LISTING_DIRTY);
            $this->em->persist($cleaning);
            if ($statusold != $cleaning->getStatus() ){
                $cleaninglog = new CleaningLog();
                $cleaninglog->setStatus($cleaning->getStatus());
                $cleaninglog->setCleaning($cleaning);
                $cleaninglog->setUpdatedat(new \DateTime());
                $this->em->persist($cleaninglog);
            }
            return $relation->getId();
        } catch (\Exception $ex) {
            return $this->render('RestaurantBundle:Exception:exception.html.twig', array('message' => $ex));
        }
    }

    //Actualizar los datos del objeto Bill
    private function updateBill($data){
        $bill = $this->entity_basic->getBill();
        try {
            foreach ($data as $value) {
                $upper = strtoupper(substr($value['name'], 0, 1));
                $rest = substr($value['name'], 1);
                $set_method = 'set'.$upper.$rest;
                //if ($value['value'] > 0){
                    if ($value['name'] == 'eind') {

                        $current = str_replace('.', '', $value['value']);
                        $current = str_replace(',', '.', $current);
                        $bill->$set_method((float)$current);
                   }
                    else {
                        $current = str_replace('.', '', $value['value']);
                        $bill->$set_method((integer)$current);
                    }
                //}
            }
            $this->entity_basic->setBill($bill);
        } catch (\Exception $ex) {
            return $this->render('RestaurantBundle:Exception:exception.html.twig', array('message' => $ex));
        }
    }

    //Actualizar los datos del objeto Card
    private function updateCard( $data){
        $card = $this->entity_basic->getCard();
        try {
            $ctrl = FALSE;
            foreach ($data as $value) {
                if ($value['name'] == 'iscc') $ctrl = TRUE;
                $upper = strtoupper(substr($value['name'], 0, 1));
                $rest = substr($value['name'], 1);
                $set_method = 'set'.$upper.$rest;
                $current = str_replace('.', '', $value['value']);
                $current = str_replace(',', '.', $current);
                $card->$set_method((float)$current);
            }
            if (!$ctrl) $card->setIscc(FALSE);
            //$em->flush();
            //var_dump($card);die;
            $this->entity_basic->setCard($card);
       //     $this->em->flush();

            //return $entity_basic;
        } catch (\Exception $ex) {
            return $this->render('RestaurantBundle:Exception:exception.html.twig', array('message' => $ex));
        }
    }

    //Actualizar los datos del formulario final
    private function updateFinal($data){
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
                $this->entity_basic->$set_method($current);
            }
        } catch (\Exception $ex) {
            return $this->render('RestaurantBundle:Exception:exception.html.twig', array('message' => $ex));
        }
    }

    //Actualizar los totales del formulario ultima pagina
    public function updateCalculos($entity = null){
        $entity = is_null($entity) ? $this->entity_basic : $entity;
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
        $entity->setKasver($entity->getTotalont() - $entity->getTotaldag() - $totalborg - $entity->getLongstay());
        $em->persist($entity);
        $em->flush();
    }

    /**
     * Deletes a Listing entity.
     *
     * @Route("/{id}/delete/", name="hotel_delete")
     * @Security("is_granted('ROLE_HOTEL_FORM')")
     * @Method("GET")
     */
    public function deleteAction($id) {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:Hotel')->find($id);
            $user = $this->get('security.token_storage')->getToken()->getUser();

            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }
            $now = new \DateTime('now');
            //echo  $entity_basic->getUpdated()->diff($now)->d ; die;
            //if (strtotime($olddate->format('d-m-Y')) > strtotime($entity_basic->getUpdated()->format('d-m-Y')) && $user->getRole() != 'ROLE_SUPERADMIN') {
            if (($entity->getUpdated()->diff($now)->d >= 2) && (!$this->isGranted('ROLE_SUPER_ADMIN'))) {
                $this->addFlash('error', 'Error! This form can not be removed.');
                return $this->redirect($this->generateUrl('hotel', array('date' => date('m-Y'))));
            }
            $rcheckin = $em->getRepository('RestaurantBundle:RCheckinHotel')->findBy(array('hotel' => $id));
            $rcheckout = $em->getRepository('RestaurantBundle:RCheckoutHotel')->findBy(array('hotel' => $id));
            foreach ($rcheckout as $value) {
                if (!empty($value->getCheckout())) {
                    $cleaning = $em->getRepository("RestaurantBundle:Cleaning")->findOneBy(array("checkout"=>$value->getCheckout()->getId()));
                    if (!is_null($cleaning)){
                        $cleaning->setCheckout(NULL);
                        $em->persist($cleaning);
                    }
                }
                

            }
            $em->flush();
            foreach ($rcheckin as $value) {
                $em->remove($value);
            }

            foreach ($rcheckout as $value) {
                $em->remove($value);
            }

            $extra = $em->getRepository('RestaurantBundle:RCleaningExtraHotel')->findBy(array('hotel' => $id));
        foreach ($extra as $value) {
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
            return $this->render('RestaurantBundle:Exception:exception.html.twig', array('message' => $ex));
        }
    }

    private function sendMail($id){
        $em = $this->getDoctrine()->getManager();

        //Crear el notificador para este formulario.
        $notifier = $em->getRepository('RestaurantBundle:Notifier')->findOneBy(array('form'=>'Hotel'));
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
     * @Security("is_granted('ROLE_HOTEL_FORM')")     *
     * @Route("/{id}/mail/", name="hotel_mail")
     */
    public function mailAction($id){
            try {
                $this->sendMail($id);
            } catch (\Exception $ex) {
                return $this->render('RestaurantBundle:Exception:exception.html.twig', array('message' => $ex));
            }
            $this->addFlash('success', 'Success! The form has been sent.');
            return $this->redirect($this->generateUrl('hotel', array('date' => date('m-Y'))));
    }

    /**
     * Displays a form to edit an existing ReceptionParking entity.
     * @Security("is_granted('ROLE_HOTEL_FORM')")
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
    * @Security("is_granted('ROLE_HOTEL_FORM')")
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

    /**
     *
     * @Route("/pdf/set/{checkin}", name="hotel_setpdf")
     * @Security("is_granted('ROLE_HOTEL_FORM')")
     * @Method("GET")
     */
    public function setPDF($checkin){
        if ($checkin == 'null'){
            $pdf = new Fpdi();
            $pdf->AddPage('L');
            $pdf->SetFont('Helvetica', 'I', 10);
            $pdf->SetTextColor(60, 58, 58);
            $pdf->SetXY(43, 130);
            $pdf->SetXY(233, 130);
            return new Response($pdf->Output(), 200, array(
                'Content-Type' => 'application/pdf'));
        }
        else {
            $checkin_obj = $this->getDoctrine()->getManager()->getRepository('RestaurantBundle:RCheckinHotel')->find($checkin);
            $checkout = $checkin_obj->getHotel()->getDated()->format('d-m-Y');
            $checkout = (new \DateTime($checkout))->add(new \DateInterval('P0Y0M' . $checkin_obj->getNights() . 'DT0H0M0S'));
            $listing = is_null($checkin_obj->getListing()) ? "" : $checkin_obj->getListing()->getNumber();
            $level = is_null($checkin_obj->getListing()) ? "" : $checkin_obj->getListing()->getLevel() . "th Floor";

            $pdf = new Fpdi('P','mm',array(210,297));
            $pdf->AddPage('L');
            $pdf->SetFont('Helvetica', 'BI', 10);
            $pdf->SetTextColor(60, 58, 58);
            $pdf->SetXY(16, 140);
            $pdf->Write(0, iconv("UTF-8", "ISO-8859-1//TRANSLIT",ucwords(strtolower($checkin_obj->getName()))));
            $pdf->SetXY(16, 145);
            $pdf->SetFont('Helvetica', 'I', 10);
            $pdf->Write(0, "Apartment ");
            $pdf->Write(0, $listing . " (" . $level. ")");
            $pdf->SetXY(16, 150);
            $pdf->SetFont('Helvetica', 'BI', 10);
            $pdf->Write(0, "Check-In: ");
            $pdf->SetFont('Helvetica', 'I', 10);
            $pdf->Write(0, $checkin_obj->getHotel()->getDated()->format('d-m-Y'));
            $pdf->SetFont('Helvetica', 'BI', 10);
            $pdf->Write(0, "  Check-Out: ");
            $pdf->SetFont('Helvetica', 'I', 10);
            $pdf->Write(0, $checkout->format('d-m-Y'));

            $pdf->SetFont('Helvetica', 'BI', 10);
            $pdf->SetXY(208, 130);
            $pdf->Write(0, iconv("UTF-8", "ISO-8859-1//TRANSLIT",ucwords(strtolower($checkin_obj->getName()))));
            $pdf->SetXY(208, 135);
            $pdf->SetFont('Helvetica', 'I', 10);
            $pdf->Write(0, "Apartment ");
            $pdf->Write(0, $listing . " (" . $level. ")");
            $pdf->SetXY(208, 140);
            $pdf->SetFont('Helvetica', 'BI', 10);
            $pdf->Write(0, "Check-In: ");
            $pdf->SetFont('Helvetica', 'I', 10);
            $pdf->Write(0, $checkin_obj->getHotel()->getDated()->format('d-m-Y'));
            $pdf->SetFont('Helvetica', 'BI', 10);
            $pdf->Write(0, "  Check-Out: ");
            $pdf->SetFont('Helvetica', 'I', 10);
            $pdf->Write(0, $checkout->format('d-m-Y'));
            return new Response($pdf->Output(), 200, array(
                'Content-Type' => 'application/pdf'));
        }
    }


    /**
     *
     * @Route("/pdf/all/{id}", name="hotel_allpdf")
     * @Security("is_granted('ROLE_HOTEL_FORM')")
     * @Method("GET")
     */
    public function allPDF($id){
        //var_dump($checkin);die;
        $checkins = $this->getDoctrine()->getManager()->getRepository('RestaurantBundle:RCheckinHotel')->findBy(array('hotel'=>$id));
        $pdf = new Fpdi('P','mm',array(210,297));
        foreach ($checkins as $checkin){
            if ($checkin->getName() != ""){
                $checkout = $checkin->getHotel()->getDated()->format('d-m-Y');
                $checkout = (new \DateTime($checkout))->add(new \DateInterval('P0Y0M' . $checkin->getNights() . 'DT0H0M0S'));
                $listing = is_null($checkin->getListing()) ? "" : $checkin->getListing()->getNumber();
                $level = is_null($checkin->getListing()) ? "" : $checkin->getListing()->getLevel() . "th Floor";

                $pdf->AddPage('L');
                $pdf->SetFont('Helvetica', 'BI', 10);
                $pdf->SetTextColor(60, 58, 58);
                $pdf->SetXY(16, 140);
                $pdf->Write(0, iconv("UTF-8", "ISO-8859-1//TRANSLIT",ucwords(strtolower($checkin->getName()))));
                $pdf->SetXY(16, 145);
                $pdf->SetFont('Helvetica', 'I', 10);
                $pdf->Write(0, "Apartment ");
                $pdf->Write(0, $listing . " (" . $level. ")");
                $pdf->SetXY(16, 150);
                $pdf->SetFont('Helvetica', 'BI', 10);
                $pdf->Write(0, "Check-In: ");
                $pdf->SetFont('Helvetica', 'I', 10);
                $pdf->Write(0, $checkin->getHotel()->getDated()->format('d-m-Y'));
                $pdf->SetFont('Helvetica', 'BI', 10);
                $pdf->Write(0, "  Check-Out: ");
                $pdf->SetFont('Helvetica', 'I', 10);
                $pdf->Write(0, $checkout->format('d-m-Y'));


                $pdf->SetXY(208, 130);
                $pdf->SetFont('Helvetica', 'BI', 10);
                $pdf->Write(0, iconv("UTF-8", "ISO-8859-1//TRANSLIT",ucwords(strtolower($checkin->getName()))));
                $pdf->SetXY(208, 135);
                $pdf->SetFont('Helvetica', 'I', 10);
                $pdf->Write(0, "Apartment ");
                $pdf->Write(0, $listing . " (" . $level. ")");
                $pdf->SetXY(208, 140);
                $pdf->SetFont('Helvetica', 'BI', 10);
                $pdf->Write(0, "Check-In: ");
                $pdf->SetFont('Helvetica', 'I', 10);
                $pdf->Write(0, $checkin->getHotel()->getDated()->format('d-m-Y'));
                $pdf->SetFont('Helvetica', 'BI', 10);
                $pdf->Write(0, "  Check-Out: ");
                $pdf->SetFont('Helvetica', 'I', 10);
                $pdf->Write(0, $checkout->format('d-m-Y'));
            }
        }
        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));

    }


}
