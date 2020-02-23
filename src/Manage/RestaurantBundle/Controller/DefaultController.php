<?php

namespace Manage\RestaurantBundle\Controller;
use Manage\RestaurantBundle\Entity\Checkin;
use Manage\RestaurantBundle\Entity\Checkout;
use Manage\RestaurantBundle\Entity\ListingChangeLog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Request;
use Manage\RestaurantBundle\Controller\ApiGuesty;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



class DefaultController extends Controller {

  //Funcionalidades para consumir servicios web de guesty
    /**
     * Displays a form to create a new Listing entity.
     *
     * @Route("/guesty/checkin/", name="get_checkin")
     * @Method("GET")
     * @Template()
     */
    public function getCheckinAction() {
       $api = new ApiGuesty();
       $rowdata = $api->checkin();
       $result = $rowdata['result']['results'];
       $status = $rowdata['status'];
       //var_dump($rowdata); die;
        $log = $this->get('logger');
        //echo ($rowdata['status']); die;
       $em = $this->getDoctrine()->getManager();
        if ($status == 200){
            foreach ($result as $current){
                //var_dump($current);die;
                //Buscar si existe el checkin en BD
                $savedcheckin = $em->getRepository('RestaurantBundle:Checkin')->findOneBy(array('idguesty'=>$current['_id']));
                //Si no exise el checkin entonces se crea uno nuevo en BD
                $listing = $this->getCurrentListing($current['listing']['_id']);
                if (is_null($savedcheckin)) {
                    $checkin = new Checkin();
                    if (!is_null($listing)) {
                        //Obtener el objeto de la reserva correspondiente
                        //$reservation = $api->reservation($current['_id']);
                        
                        $checkin->setListing($listing->getId());
                        $checkin->setTime(new \DateTime($current['checkIn']));
                        $date = new \DateTime($current['checkIn']);
                        $checkin->setDate(new \DateTime($date->format('Y-m-d')));
                        $checkin->setName($current['guest']['fullName']);
                        $checkin->setConfcode($current['confirmationCode']);
                        $checkin->setIdguesty($current['_id']);
                        $checkin->setSource($current['source']);
                        $checkin->setNights($current['nightsCount']);
                        $checkin->setGuests($current['guestsCount']);
                        $checkin->setStatus($current['status']);
                        $checkin->setNote(isset($current['guest']['notes']) ? $current['guest']['notes'] : null);
                        $checkin->setEmail(isset($current['guest']['email']) ? $current['guest']['email'] : null);
                        $checkin->setPhone(isset($current['guest']['phone']) ? $current['guest']['phone'] : null);
                        $invoice = $this->getMoneyValues($current['money']['invoiceItems']);
                        $checkin->setBetalen($invoice['accommodation'] + $invoice['vat']);
                        $checkin->setVoldan($current['money']['hostPayout'] - $current['money']['balanceDue']);
                        /*$guest = $api->guest($current['guestId']);
                        if ($guest['status'] == 200) {
                            if (isset($guest['result']['email'])) $checkin->setEmail($current['guest']['email']);
                            if (isset($guest['result']['phone'])) $checkin->setPhone($current['guest']['phone']);
                        }*/
                        if (isset($current['canceledAt']))
                            $checkin->setCanceledat(new \DateTime($current['canceledAt']));
                        $checkin->setUpdatedat(new \DateTime(date('Y-m-d h:m:s')));
                        $em->persist($checkin);
                        $em->flush();
                        $log->alert(' Checkin created. ID:'.$current['_id']. ' Listing: '. $current['listing']['title']);
                    }
                }
                else{


                    if (!is_null($listing)) {
                        //Obtener el objeto de la reserva correspondiente
                        //$reservation = $api->reservation($current['_id']);

                            $savedcheckin->setListing($listing->getId());
                            $savedcheckin->setTime(new \DateTime($current['checkIn']));
                            $date = new \DateTime($current['checkIn']);
                            $savedcheckin->setDate(new \DateTime($date->format('Y-m-d')));
                            $savedcheckin->setName($current['guest']['fullName']);
                            $savedcheckin->setConfcode($current['confirmationCode']);
                            $savedcheckin->setSource($current['source']);
                            $savedcheckin->setNights($current['nightsCount']);
                            $savedcheckin->setGuests($current['guestsCount']);
                            $savedcheckin->setStatus($current['status']);
                            $savedcheckin->setNote(isset($current['guest']['notes']) ? $current['guest']['notes'] : null);
                            //$money = $api->reservationMoney($current['_id']);
                           // if (isset($money['result']['money']['fareAccommodation']) && $money['result']['money']['fareAccommodation'] != 0){
                            $invoice = $this->getMoneyValues($current['money']['invoiceItems']);
                            $savedcheckin->setBetalen($invoice['accommodation'] + $invoice['vat']);   
                            //} 

                            //if (isset($money['result']['money']['balanceDue']) && $money['result']['money']['balanceDue'] != 0){
                                $savedcheckin->setVoldan($current['money']['hostPayout'] - $current['money']['balanceDue']);
                            //}

                            /*if (isset($money['result']['money']['totalRefunded']) && $money['result']['money']['totalRefunded'] != 0){
                                $savedcheckin->setVoldan($savedcheckin->getVoldan()-$money['result']['money']['totalRefunded']);
                            }*/
                            if (isset($current['canceledAt']))
                                $savedcheckin->setCanceledat(new \DateTime($current['canceledAt']));
                            $savedcheckin->setUpdatedat(new \DateTime(date('Y-m-d h:m:s')));
                            $em->persist($savedcheckin);
                            $em->flush();
                            $log->alert(' Checkin updated. ID:'.$current['_id']. ' Listing: '. $current['listing']['title']);


                    }

                }
                //Buscar si existe el checkout en BD
                $savedcheckout = $em->getRepository('RestaurantBundle:Checkout')->findOneBy(array('idguesty'=>$current['_id']));
                //Si no exise el checkout entonces se crea uno nuevo en BD
                $listing = $this->getCurrentListing($current['listing']['_id']);
                if (is_null($savedcheckout)) {
                    $checkout = new Checkout();

                    if (!is_null($listing)) {
                        //Obtener el objeto de la reserva correspondiente

                            $checkout->setListing($listing->getId());
                            $checkout->setTime(new \DateTime($current['checkOut']));
                            $date = new \DateTime($current['checkOut']);
                            $checkout->setDate(new \DateTime($date->format('Y-m-d')));
                            $checkout->setName($current['guest']['fullName']);
                            $checkout->setConfcode($current['confirmationCode']);
                            $checkout->setIdguesty($current['_id']);
                            $checkout->setSource($current['source']);
                            $checkout->setNights($current['nightsCount']);
                            $checkout->setGuests($current['guestsCount']);
                            $checkout->setStatus($current['status']);
                            $checkout->setEmail(isset($current['guest']['email']) ? $current['guest']['email'] : null);
                            $checkout->setPhone(isset($current['guest']['phone']) ? $current['guest']['phone'] : null);
                            
                            $checkout->setUpdatedat(new \DateTime(date('Y-m-d h:m:s')));
                            $em->persist($checkout);
                            $em->flush();
                            //$log->alert(' Checkout created. ID:' . $current['_id'] . ' Listing: ' . $current['listing']['title']);

                    }
                }

                else{
                    if (!is_null($listing)) {

                            $savedcheckout->setListing($listing->getId());
                            $savedcheckout->setTime(new \DateTime($current['checkOut']));
                            $date = new \DateTime($current['checkOut']);
                            $savedcheckout->setDate(new \DateTime($date->format('Y-m-d')));
                            $savedcheckout->setName($current['guest']['fullName']);
                            $savedcheckout->setConfcode($current['confirmationCode']);
                            $savedcheckout->setSource($current['source']);
                            $savedcheckout->setNights($current['nightsCount']);
                            $savedcheckout->setGuests($current['guestsCount']);
                            $savedcheckout->setStatus($current['status']);

                            $savedcheckout->setUpdatedat(new \DateTime(date('Y-m-d h:m:s')));
                            $em->persist($savedcheckout);
                            $em->flush();
                            //$log->alert(' Checkout updated. ID:'.$current['_id']. ' Listing: '. $current['listing']['title']);

                    }
                }
            }

        }
        //else $log->alert('No data recovered');
        die;
    }

    /**
     *
     * @Route("/guesty/canceled", name="get_canceled")
     * @Method("GET")
     * @Template()
     */

    public function getCanceledAction() {
        $em = $this->getDoctrine()->getManager();
        $api = new ApiGuesty();
        $result = $api->canceledcheckin();
        var_dump($result);
        echo 'done';
        die;


    }




      /**
     *
     * @Route("/guesty/updater/", name="guesty_updater")
     * @Method("GET")
     * @Template()
     */

    public function updateReservationsAction() {
        $em = $this->getDoctrine()->getManager();
        $consulta = $em->createQuery('SELECT o FROM RestaurantBundle:Checkin o WHERE  o.date >= :dated ORDER BY o.date ASC ');
        $consulta->setParameter('dated',  date('Y-m-d') );
        $consulta->setMaxResults(50);

        $savedcheckin = $consulta->getResult();
        $api = new ApiGuesty();
        foreach ($savedcheckin as $checkin){
            $result = $api->reservation($checkin->getIdguesty());
            $reserva = $result['result'];
            $status = $result['status'];
            if ($status == 200){
                $invoice = $this->getMoneyValues($reserva['money']['invoiceItems']);
                $checkin->setBetalen($invoice['accommodation'] + $invoice['vat']);
                $checkin->setStatus($reserva['status']);
                if (isset($reserva['canceledAt']))
                    $checkin->setCanceledat(new \DateTime($reserva['canceledAt']));

                $em->persist($checkin);

                $checkout = $em->getRepository('RestaurantBundle:Checkout')->findOneBy(array('confcode' => $checkin->getConfcode()));
                if (!is_null($checkout)) {
                    $checkout->setStatus($reserva['status']);
                    $em->persist($checkout);
                }
            }
        }
        $em->flush();
        echo 'done';
        die;
    }

    private function getMoneyValues($invoiceItems) {
        $result['vat'] = 0;
        $result['accommodation'] = 0;
        foreach ($invoiceItems as $item) {
            if ($item['title'] == 'Accommodation fare'){
                $result['accommodation'] = $item['amount'];
            }
            if ($item['title'] == 'VAT'){
                $result['vat'] = $item['amount'];
            }
        }
        return $result;
    }




    /**
     *Tarea Programada. Almacenar diariamente los precios de las habitaciones
     * 
     * @Route("/guesty/listing/", name="get_listing")
     * @Method("GET")
     * @Template()
     */
    public function getListingAction() {
        $api = new ApiGuesty();
        $rowdata = $api->listinglist();
        $result = $rowdata['result']['results'];
        $status = $rowdata['status'];
        $em = $this->getDoctrine()->getManager();
        if ($status == 200){
            foreach ($result as $current){
                //var_dump($current['prices']['basePrice']);die;
                $listing = $this->getCurrentListing($current['_id']);
                if (!is_null($listing)){
                    $listing->setDetails($current['title']);
                    //$listing->setIdguesty($current['_id']);
                    $listing->setType($current['tags']);
                    $em->persist($listing);
                    echo 'updated  --  '.$listing->getDetails().'<br/>';
                }
            }
        }
        $em->flush();
        die;
    }
    /**
     *
     * @Route("/guesty/integrations/", name="get_integrations")
     * @Method("GET")
     * @Template()
     */
    public function getIntegrationsAction() {
       $api = new ApiGuesty();
       var_dump($api->integrations());die;
    }

    private function getCurrentListing($idGesty){
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('RestaurantBundle:Listing')->findOneBy(array('idguesty' => $idGesty));
    }
    
        /**
     *
     * @Route("/external/", name="external")
     * @Method("GET")
     * @Template()
     */
    public function externalAction() {
       return $this->render('RestaurantBundle:Default:externallogin.html.twig', array('error'=>FALSE));
    }
    
       /**
     *
     * @Route("/external/confirmphrase/", name="external_confirm_phrase")
     * @Method("POST")
     * @Template()
     */
    public function confirmphraseAction(Request $request) {
        $form = $request->get('external');
        $em = $this->getDoctrine()->getManager();
        $conf = $em->getRepository('RestaurantBundle:Parameters')->findOneBy(array('variable'=>'phrase'));
        if ($conf->getValue() == $request->get('phrase')){
           return $this->redirectToRoute('cleaning', array('date'=>date('d-m-Y')));
        }
        else{
            return $this->render('RestaurantBundle:Default:externallogin.html.twig', array('error'=>'Phrase does not match!'));

        }
    }


    /**
     *
     * @Route("/guesty/listingcalendar/", name="listingcalendar")
     * @Method("GET")
     * @Template()
     */
    public function listingcalendarAction(){
        $api = new ApiGuesty();
        $dataguesty = $api->getListingCalendar('58a5dffa3798420400c8e691', '2018-09-01', '2018-09-04');
        var_dump($dataguesty);die;
    }

    /**
     *
     * @Route("/guesty/rules", name="rules")
     * @Method("GET")
     * @Template()
     */
    public function rulesAction(){
       $this->halfPrice();
        die;
    }



    private function halfPrice(){
        //Si es mas de las 6 pm
        $hours = date('H');
        $minutes = date('i');
        $api = new ApiGuesty();
        $em = $this->getDoctrine()->getManager();
        if ($hours >= 18 && $minutes != '00'){
            $listings = $em->getRepository('RestaurantBundle:Listing')->findAll();
            foreach ($listings as $listing){
                $dataguesty = $api->getListingCalendar($listing->getIdguesty(), date('Y-m-d'), date('Y-m-d'));
                if ($dataguesty['status'] == 200 && $dataguesty['result']['status'] == 'available'){
                    $newprice = (integer)($dataguesty['result']['price'] - ($dataguesty['result']['price']*(0.5)));
                    if ($newprice < 49) $newprice = 49;
                    $result =  $api->setListingCalendar(array("listings"=>$listing->getIdguesty(), "from"=>date('Y-m-d'), "to"=>date('Y-m-d'),  "price"=>$newprice, "note"=>"Updated Automatically from log.towerleisure.nl"));
                    //$result =  $api->setListingCalendar(array("listings"=>$listing->getIdguesty(), "from"=>"2019-03-01", "to"=>"2019-03-01",  "price"=>$newprice, "note"=>"Updated Automatically from log.towerleisure.nl"));
                    echo $result['status'];
               }
            }
        }
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

    /**
     *
     * @Route("/reservation/", name="reservation")
     * @Method("GET")
     * @Template()
     */
    public function reservationAction(){
        $response = new JsonResponse();
        //SELECT COUNT(email) as cantidad, id, email, Listing, name FROM `checkin` WHERE date > '2018-09-01' GROUP BY email having cantidad > 1 ORDER BY `time` DESC
        $em = $this->getDoctrine()->getManager();
       // $consulta = $em->createQuery('SELECT COUNT(c.email) as cantidad, c.email, c.name, c.listing, c.date  FROM RestaurantBundle:Checkin c WHERE c.date > :start AND c.date <= :end GROUP BY c.email HAVING cantidad >:cant');
        $consulta = $em->createQuery('SELECT COUNT(c.email) as cantidad, c.email, c.name  FROM RestaurantBundle:Checkin c WHERE c.date > :start AND c.date <= :ends AND c.status LIKE :status GROUP BY c.email HAVING cantidad >:cant');
        $fecha = $end = new \DateTime('tomorrow');
        $consulta->setParameter('start', $fecha->format('Y-m-d'));
        $end = new \DateTime('now + 7 days');
        $consulta->setParameter('ends', $end->format('Y-m-d'));
        $consulta->setParameter('cant', 1);
        $consulta->setParameter('status', 'confirmed');
        $checkin = $consulta->getResult();

        $result = array();
        foreach ($checkin as $item){
            //Por cada email repetido, dame todas sus reservas correspondientes a las fechas del rango
            $current = array();
            //echo $item['email'];die;
            $current['name'] = $item['name'].' ('.$item['email'].')';
            $consulta = $em->createQuery('SELECT c FROM RestaurantBundle:Checkin c WHERE c.date > :start AND c.date <= :ends AND c.email LIKE :currentemail AND c.status LIKE :status');
            $consulta->setParameter('start', $fecha->format('Y-m-d'));
            $consulta->setParameter('ends', $end->format('Y-m-d'));
            $consulta->setParameter('currentemail', $item['email']);
            $consulta->setParameter('status', 'confirmed');
            $reservas = $consulta->getResult();
            $current['reservations']=$reservas;
            $result[] = $current;
        }

        if (count($result) > 0){
            $mails_array = explode(';','lianaisabel@gmail.com;ymauri@gmail.com');
            $mail_customer = \Swift_Message::newInstance()
                ->setFrom('info@log.towerleisure.nl')
                ->setTo($mails_array)
                ->setSubject("Reservations Summary")
                ->setBody($this->renderView('RestaurantBundle:Default:reservationmail.html.twig', array(
                    'data' => $result,
                    'listing' => $this->getActiveListing(),
                )))
                ->setContentType("text/html");

            $this->get('mailer')->send($mail_customer);
            return $response->setData('done');
        }

        //var_dump($result);die;
    }
    /**
     * Website main page.
     *
     * @Route("/", name="home")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        //var_dump($this->isGranted('ROLE_CLEANING'));die;

        $em = $this->getDoctrine()->getManager();
        $recepties = $em->getRepository('RestaurantBundle:Reception')->findBy(array('updated'=>new\DateTime()));
        $hotels = $em->getRepository('RestaurantBundle:Hotel')->findBy(array('dated'=>new\DateTime()));
        $skybars = $em->getRepository('RestaurantBundle:Skybar')->findBy(array('updated'=>new\DateTime()));
        $services = $em->getRepository('RestaurantBundle:CashClosure')->findBy(array('updated'=>new\DateTime()));
        $logs = $em->getRepository('RestaurantBundle:Log')->findBy(array('updated'=>new\DateTime()));
        $turnovers = $em->getRepository('RestaurantBundle:Turnover')->findBy(array('updated'=>new\DateTime()));
        $kasboeks = $em->getRepository('RestaurantBundle:Kasboek')->findBy(array('updated'=>new\DateTime()));
        $kasboekshotel = $em->getRepository('RestaurantBundle:KasboekHotel')->findBy(array('updated'=>new\DateTime()));
        return $this->render('RestaurantBundle:Default:index.html.twig', array(
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
     *
     * @Route("/parameters/", name="parameters")
     * @Security("is_granted('ROLE_FORMS_SETTINGS')")
     *
     * @Template()
     */
    public function parametersAction() {
        $em = $this->getDoctrine()->getManager();
        return $this->render('RestaurantBundle:Default:parameters.html.twig', array(
            'iva'       => $em->getRepository('RestaurantBundle:Parameters')->getFieldsIva(),
            'hotel'     => $em->getRepository('RestaurantBundle:Parameters')->getFieldsHotel(),
            'general'   => $em->getRepository('RestaurantBundle:Parameters')->getFieldsGeneral(),
            'rules'     => $em->getRepository('RestaurantBundle:Parameters')->getFieldsRules(),
        ));
    }

    /**
     *
     * @Route("/parameters/{type}/", name="parameters_update")
     * @Security("is_granted('ROLE_FORMS_SETTINGS')")
     *
     * @Template()
     */
    public function parametersUpdateAction(Request $request, $type) {
        $em = $this->getDoctrine()->getManager();
        if ($type == 'iva'){
            //actulaizar los datos de parameters
        }
        if ($type == 'hotel'){
            $hotel = $em->getRepository('RestaurantBundle:Parameters')->getFieldsHotel();
            foreach ($hotel as $item){
                if ($item->getVariable() == 'parking_quantity')
                    $item->setValue((double)$request->get($item->getVariable()));
                $item->setValue($request->get($item->getVariable()));
            }
            //$request->get('turism-taxes');
        }

        if ($type == 'general'){
            $general = $em->getRepository('RestaurantBundle:Parameters')->getFieldsGeneral();
            foreach ($general as $item){
                $item->setValue($request->get($item->getVariable()));
            }
            //$request->get('turism-taxes');
        }

        if ($type == 'rules'){
            $general = $em->getRepository('RestaurantBundle:Parameters')->getFieldsRules();
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

