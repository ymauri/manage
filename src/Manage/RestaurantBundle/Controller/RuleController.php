<?php

namespace Manage\RestaurantBundle\Controller;

use Manage\RestaurantBundle\Entity\Listing;
use Manage\RestaurantBundle\RestaurantBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\ListingChangeLog;
use Manage\RestaurantBundle\Entity\Rule;
use Manage\RestaurantBundle\Entity\RuleLog;
use Manage\RestaurantBundle\Form\RuleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Manage\RestaurantBundle\Controller\ApiGuesty;
use Manage\RestaurantBundle\Entity\ListingCalendar;
use Symfony\Component\Validator\Constraints\DateTime;

class RuleController extends Controller
{
    /**
     * Listado de reglas ordenado por la prioridad
     *
     * @Route("/rule/", name="rule_admin")
     * @Method("GET")
     * @Template()
     */
    public function adminAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER') {
            $em = $this->getDoctrine()->getManager();
            $rules = $em->getRepository('RestaurantBundle:Rule')->findBy(array(), array('priority' => 'ASC'));
            return $this->render('RestaurantBundle:Rule:index.html.twig', array('rules' => $rules));
        } else return $this->render('AdminBundle:Exception:error403.html.twig');
    }

    /**
     * Editar Reglas
     *
     * @Route("/rule/{id}/edit", name="rule_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:Rule')->find($id);
            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));
            }
            $form = $this->createForm(new RuleType(), $entity, array(
                'action' => $this->generateUrl('rule_edit', array('id' => $entity->getId())),
                'method' => 'PUT',
            ));

            $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary')));
            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Accion del formulario para salvar los datos editados.
     *
     * @Route("/rule/{id}", name="rule_update")
     * @Method("PUT")
     * @Template("RestaurantBundle:Rule:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:Rule')->find($id);
            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));
            }
            $form = $this->createForm(new RuleType(), $entity, array(
                'action' => $this->generateUrl('rule_edit', array('id' => $entity->getId())),
                'method' => 'PUT',
            ));
            $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary')));
            $form->handleRequest($request);
            if ($form->isValid()) {
                //Actualizar los valores de máximos y mínimos para los deptos de las reglas.
                $listings = $em->getRepository('RestaurantBundle:Listing')->findAll();
                $arraylistings = array();
                foreach ($listings as $listing) {
                    $arraylistings[$listing->getIdguesty()]['min'] = $listing->getMinprice();
                    $arraylistings[$listing->getIdguesty()]['max'] = $listing->getMaxprice();
                }
                $entity->setPricesbylisting($arraylistings);
                $settings = $em->getRepository('AdminBundle:Parameters')->getFieldsRules();
                $arraysettings = array();
                foreach ($settings as $setting) {
                    $arraysettings[$setting->getVariable()] = $setting->getValue();
                }
                $entity->setPricesbytype($arraysettings);
                $em->flush();
                $this->addFlash('success', 'Success! The Rule has been changed.');
                return $this->redirect($this->generateUrl('rule_admin', array('id' => $id)));
            }
            return array(
                'entity' => $entity,
                '$form' => $form->createView(),
            );
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Adicionar una regla
     *
     * @Route("/rule/new/", name="rule_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER') {
            $newrule = new Rule();
            $form = $this->createForm(new RuleType(), $newrule, array(
                'action' => $this->generateUrl('rule_create'),
                'method' => 'POST',
            ));

            $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary', "style" => "margin-top: 15px;")));
            $em = $this->getDoctrine()->getManager();
            return $this->render('RestaurantBundle:Rule:new.html.twig', array(
                'form' => $form->createView(),
                'listings' => $em->getRepository('RestaurantBundle:Listing')->findAll()));
        }
    }

    /**
     * Accion del formulario para salvar los datos de una nueva regla.
     *
     * @Route("/rule/", name="rule_create")
     * @Method("POST")
     * @Template("RestaurantBundle:Rule:edit.html.twig")
     */
    public function createAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER') {
            $entity = new Rule();
            $form = $this->createForm(new RuleType(), $entity, array(
                'action' => $this->generateUrl('rule_new'),
                'method' => 'POST',
            ));
            $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary')));
            $form->handleRequest($request);
            //var_dump($form->isValid());die;
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Success! The rule has been created.');
                return $this->redirect($this->generateUrl('rule_admin'));
            }
            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );
        }

    }

    /**
     *
     * @Route("/{id}/priority/", name="rule_priority")
     * @Method("POST")
     */
    public function orderAction($id, Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:Rule')->find($id);
            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));
            }
            if ($request->get('priority') > 0) {
                $entity->setPriority((integer)$request->get('priority'));
                $em->flush();
            }
            $this->addFlash('success', 'Success! The Rule has been updated.');

            return $this->redirect($this->generateUrl('rule_admin'));
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Acción registrada en el CronJob del servidor para la ejecución automática de las reglas.
     *
     * @Route("/rule/execute/", name="rule_execute")
     * @Method("GET")
     * @Template()
     *
     */
    public function excecuteRulesAction()
    {
        $em = $this->getDoctrine()->getManager();
        //Buscar las reglas activas ordenadas por la prioridad
        $rules = $em->getRepository('RestaurantBundle:Rule')->findBy(array('active' => true), array('priority' => "ASC"));
        foreach ($rules as $activerule) {
            //Ejecutar Reglas de tipo "Tarea programada"
            if (!$activerule->getIshook()) {
                $method = $activerule->getMethod();
                $this->$method($activerule);
            } else {
                //Ejecutar reglas de tipo Hook
                $this->executePendingHook($activerule);
            }
        }

        die (date('H:i:s') . ' end');
    }

    /**
     * Acción subscrita al hook reservation.new de Guesty.
     *
     * @Route("/rule/executehook/", name="rule_execute_hook")
     * @Method("POST")
     * @Template()
     *
     */
    //TODO Modificar comportamiento para que el usuario pueda escoger el hook al que se subordina la regla que está creando.
    public function excecuteHookRulesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //Buscar las reglas activas ordenadas por la prioridad (reglas de tipo hook)
        $rules = $em->getRepository('RestaurantBundle:Rule')->findBy(array('active' => true, 'ishook' => true), array('priority' => "ASC"));
        $peticion = (array)json_decode($request->getContent());
        $reservation = (array)($peticion['reservation']);
        foreach ($rules as $activerule) {
            $method = $activerule->getMethod();
            $this->$method($activerule, $reservation);
            echo($activerule->getId() . "   " . date('H:i:s') . ' end');
        }
        die;
    }

    /**
     * Acción subscrita al hook reservation.update de Guesty.
     *
     * @Route("/hook/rollback/", name="rule_rollback")
     * @Method("POST")
     * @Template()
     *
     */
    public function ruleRollbackAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $peticion = (array)json_decode($request->getContent());
        $reservation = (array)($peticion['reservation']);
        $api = new ApiGuesty();
        //Si el estado de la reserva es cancelado entonces hacer las acciones.
        if ($reservation['status'] == Nomenclator::LISTING_CANCELED){
            $checkin = new\DateTime($reservation['checkIn']);
            $listings = $em->getRepository("RestaurantBundle:Listing")->findAll();
            //Para que solo tome en cuenta la cantidad de noches y no tome el día del chekout
            for ($i = 1; ($i<=$reservation['nightsCount']); $i++){
                $ruleslog = $em->getRepository("RestaurantBundle:RuleLog")->getRuleApplied($checkin);
                //Si se aplicó alguna modificación en ese día, entonces encontrar qué regla se aplicó.
                if (!is_null($ruleslog) && count($ruleslog) > 0){
                    foreach ($ruleslog as $id => $rule) {
                        $calenadrios = array();
                        foreach ($listings as $listing){
                            $listingcalendar = $api->getListingCalendar($listing->getIdguesty(), $checkin->format('Y-m-d'), $checkin->format('Y-m-d'));
                            if ($listingcalendar['status'] == 200 && count($listingcalendar['result']) > 0 && $listingcalendar['result'][0]['status'] == Nomenclator::LISTING_AVAILABLE) {
                                $calenadrios[$listingcalendar['result'][0]['listingId']] = $listingcalendar['result'][0];
                            }
                        }
                        //Invertir las condiciones bajo las cuales se ejecuta la regla
                        switch ($rule[0]->getRule()->getCond()){
                            case "listing_available_less":
                                echo "listing_available_less <br/>";
                                if (! count($calenadrios) <= $rule[0]->getRule()->getConditionvalue()){
                                    $this->rollBackRule($rule, $calenadrios);
                                }
                                break;
                            case "listing_available_more":
                                if (! count($calenadrios) >= $rule[0]->getRule()->getConditionvalue()){
                                    $this->rollBackRule($rule, $calenadrios);
                                }
                                break;
                            case "none_condition":
                                    $this->rollBackRule($rule, $calenadrios);
                                break;
                        }
                    }
                }
                $checkin = $checkin->add(new \DateInterval('P0Y0M1DT0H0M0S'));
            }
        }
        die;
    }

    private function rollBackRule($rulelogs, $calendarios){
        $api = new ApiGuesty();
        $em = $this->getDoctrine()->getManager();
        foreach ($rulelogs as $rulelog){
            $calendar = $calendarios[$rulelog->getListing()];
            //print_r(array("listings" => $calendar['listingId'], 'from' => $rulelog->getCheckin()->format('Y-m-d'), "to" => $rulelog->getCheckin()->format('Y-m-d'), /*"price" => (integer)$rulelog->getOldprice(),*/ "note" => "RollBack"));

            $result = $api->setListingCalendar(array("listings" => $calendar['listingId'], 'from' => $rulelog->getCheckin()->format('Y-m-d'), "to" => $rulelog->getCheckin()->format('Y-m-d'), /*"price" => (integer)$rulelog->getOldprice(),*/ "note" => "RollBack"));
            if ($result['status'] == 200){
                $em->remove($rulelog);
            }
        }
        $em->flush();
    }


    /**
     * Ejecutar el el hook de reservation.new pendiente, cuyas reservas están almacenadas en base de datos.
     * estas reservas se asocian a la regla que las ha generado.
     * El método se invoca finalmente en la llamada de la tarea programada de las reglas. (Rule/execute)
     * @param Rule $rule El objeto Regla
     * */
    private function executePendingHook($rule)
    {
        $em = $this->getDoctrine()->getManager();
        $pending = null;
        switch ($rule->getMethod()) {
            case 'changepricenow':
                //Fechas en las que se debe aplicar la regla.
                $dates = $em->getRepository("RestaurantBundle:ListingCalendar")->getDates($rule->getId());
                foreach ($dates as $date) {
                    //Buscar la cantidad de reservas disponibles en esa fecha
                    $reservations = $em->getRepository("RestaurantBundle:ListingCalendar")->findBy(array("checkin" => new \DateTime($date), "rule" => $rule->getId()));
                    //Verificar el día de la semana
                    //Si está marcado el día de la semana || Si está en blanco el campo || si el array está vacío
                    if ($rule->hasWeekDates($date)) {
                        //Si la regla aplica por tipos de departamentos
                        // Buscar los deartamentos del tipo correspondiente
                        if ($rule->getBytype()) {
                            $pending = $em->getRepository("RestaurantBundle:ListingCalendar")->getListingsByTypeDate($rule->getId(), $date, $rule->getTypeofapartment());
                        } else {
                            //Si la regla es por depto, entonces buscar los deptos correspondientes
                            $pending = $em->getRepository("RestaurantBundle:ListingCalendar")->getListingsByGuestyDate($rule->getId(), $date, $rule->getApartments());
                        }
                        //break;
                        switch ($rule->getCond()) {
                            case 'listing_available_more':
                                if (count($reservations) >= $rule->getConditionvalue()) {
                                    $this->dopendingchangepricenow($rule, $pending);
                                }
                                break;
                            case 'listing_available_less':
                                echo "Pendientes: " . count($reservations) . "    Condicion: " . $rule->getConditionvalue() . "<br/><br/>";
                                if (count($reservations) <= $rule->getConditionvalue()) {
                                    $this->dopendingchangepricenow($rule, $pending);
                                }
                                break;
                            case 'none_condition':
                                $this->dopendingchangepricenow($rule, $pending);
                                break;
                        }
                    }
                }

                foreach ($pending as $calendar)
                    $em->remove($calendar);
                //Elminar los calenadrios comprometidos
                $em->flush();
                break;
        }
    }

    /**
     * Funcion que valida las condiciones de las reglas para que posteriormente se apliquen los cambios a los departamentos.
     * @param Rule $rule El objeto Regla
     * @param Request $request La Petición que proviene de la ejecución del hook.
     * */
    private function changepricenow($rule, $reservation = null)
    {
        $em = $this->getDoctrine()->getManager();
        $api = new ApiGuesty();
        $listings = $em->getRepository('RestaurantBundle:Listing')->findAll();
        $fecha = !is_null($rule->getDaysahead()) ? new \DateTime('today + ' . $rule->getDaysahead() . ' days') : new \DateTime();
        //Si la regla es de tipo cronJob
        if (!$rule->getIshook()) {
            //Validar el tiempo en el que se ejecuta la regla
            if ($rule->hasWeekDates($fecha) && ($rule->hasOnlyBeginingDate() || $rule->hasOnlyEndingDate() || $rule->hasRangeDate()) && $rule->canExecuteNow()) {
                switch ($rule->getCond()) {
                    case 'listing_available_more':
                        $listingcalendar = $this->getListingCalendar($rule, $listings);
                        $conditionvalue = 0;
                        $listings_obj = array();
                        $reallistingcalendar = array();
                        foreach ($listingcalendar as $listing) {
                            if ($listing['status'] == 200) {
                                if ($listing['result'][0]['status'] == Nomenclator::LISTING_AVAILABLE) {
                                    $conditionvalue++;
                                    //$listings_obj[$listing['result'][0]['listingId']] = $em->getRepository('RestaurantBundle:Listing')->findOneBy(array('idguesty'=>$listing['result'][0]['listingId']));
                                    //Pedir el listing a guesty
                                    $listings_obj[$listing['result'][0]['listingId']] = $api->listingtag($listing['result'][0]['listingId']);
                                    $reallistingcalendar[] = $listing;
                                }
                            }
                        }
                        //var_dump($listings_obj);die;
                        if ($rule->getConditionvalue() <= $conditionvalue) {
                            $this->dochangepricenow($rule, $reallistingcalendar, $listings_obj);
                        }
                        break;
                    case 'listing_available_less':
                        $listingcalendar = $this->getListingCalendar($rule, $listings);
                        $conditionvalue = 0;
                        $listings_obj = array();
                        $reallistingcalendar = array();
                        foreach ($listingcalendar as $listing) {
                            if ($listing['status'] == 200) {
                                if ($listing['result'][0]['status'] == Nomenclator::LISTING_AVAILABLE) {
                                    $conditionvalue++;
                                    //$listings_obj[$listing['result'][0]['listingId']] = $em->getRepository('RestaurantBundle:Listing')->findOneBy(array('idguesty'=>$listing['result'][0]['listingId']));
                                    //Pedir el listing a guesty
                                    $listings_obj[$listing['result'][0]['listingId']] = $api->listingtag($listing['result'][0]['listingId']);
                                    $reallistingcalendar[] = $listing;
                                }
                            }
                        }
                        if ($rule->getConditionvalue() >= $conditionvalue) {
                            $this->dochangepricenow($rule, $reallistingcalendar, $listings_obj);
                        }
                        break;
                    case 'none_condition':
                        $listingcalendar = $this->getListingCalendar($rule, $listings);
                        $listings_obj = array();
                        $reallistingcalendar = array();
                        foreach ($listingcalendar as $listing) {
                            if ($listing['status'] == 200) {
                                if ($listing['result'][0]['status'] == Nomenclator::LISTING_AVAILABLE) {
                                    //Pedir el listing a guesty
                                    $listings_obj[$listing['result'][0]['listingId']] = $api->listingtag($listing['result'][0]['listingId']);
                                    $reallistingcalendar[] = $listing;

                                }
                            }
                        }
                        $this->dochangepricenow($rule, $reallistingcalendar, $listings_obj);
                        break;
                }
            }

        } //Entonces la regla es de tipo webhook
        else if (!is_null($reservation)) {
            $checkin = new\DateTime($reservation['checkIn']);
            $checkout = new\DateTime($reservation['checkOut']);
            //Para que solo tome en cuenta la cantidad de noches y no tome el día del chekout
            $checkout = $checkout->modify("-1 day");
            $dias = is_null($rule->getStartingfrom()) ? 0 : $rule->getStartingfrom();
            $starting = new \DateTime("today + " . $dias . " days");
            if (strtotime($checkin->format('Y-m-d')) > strtotime($starting->format('Y-m-d'))) {
                //Iterar por cada departamento para almacenar el listado de disponibilidad
                //$dif = strtotime($checkout->format('Y-m-d')) - strtotime($checkin->format('Y-m-d'));
                $id_listings = array();
                foreach ($listings as $i => $list) {
                    $guesty_calendar = $api->getListingCalendar($list->getIdguesty(), $checkin->format('Y-m-d'), $checkout->format('Y-m-d'));
                    if ($guesty_calendar['status'] == 200 && count($guesty_calendar['result']) > 0) {
                        foreach ($guesty_calendar['result'] as $calendar) {
                            //Almacenar los calendarios que pueden afectarse con la ejecución del hook.
                            $obj = $em->getRepository("RestaurantBundle:ListingCalendar")->findOneBy(array('idcalendar' => $calendar["_id"], 'rule' => $rule->getId()));
                            if ($calendar['status'] == Nomenclator::LISTING_AVAILABLE) {
                                $calendarlist = is_null($obj) ? new ListingCalendar() : $obj;
                                $calendarlist->setIdcalendar($calendar["_id"]);
                                $calendarlist->setCheckin(new \DateTime($calendar['date']));
                                $calendarlist->setStatus($calendar['status']);
                                $calendarlist->setPrice($calendar['price']);
                                $calendarlist->setListing($calendar['listingId']);
                                $calendarlist->setApplied(false);
                                $calendarlist->setRule($rule);
                                $em->persist($calendarlist);
                            } else if (!is_null($obj)) {
                                $em->remove($obj);
                            }
                        }
                    }
                    $em->flush();
                }
            }
        }
    }

    /**
     * Este es el método que aplica que cambio sobre el departamento en Guety.
     * Solamente se usa para las reglas de tipo Tarea Programada.
     * @param Rule $rule El objeto Regla
     * @param array $calendarlist Listado de los calendarios que serán afectados en la ejecución de la regla.
     * @param array $listingobjs Listado de listings indexados por los id de los calendarios donde se actualizan los precios.
     *
     * */
    public function dochangepricenow($rule, $calendarlist, $listingobjs = array())
    {
        try {
            $api = new ApiGuesty();
            $em = $this->getDoctrine()->getManager();
            //Evaluar los tipos de condiciones que pueda tener la regla.
            foreach ($calendarlist as $listing) {
                if ($listing['status'] == 200) {
                    $listing_obj = $listingobjs[$listing['result'][0]['listingId']];
                    $listingmaxmin = $rule->getPricesbylisting();
                    $prices = $listingmaxmin[$listing_obj['result']['_id']];
                    $max = $prices['max'];
                    $min = $prices['min'];
                    $newprice = ($rule->getUnit() == 'euro') ? $rule->getActionvalue() : ((double)$listing['result'][0]['price'] * $rule->getActionvalue()) / 100;
                    $fecha = (!is_null($rule) && !is_null($rule->getDaysahead())) ? new \DateTime('today + ' . $rule->getDaysahead() . ' days') : new \DateTime();
                    switch ($rule->getAction()) {
                        case 'listing_change_price':
                            if ($newprice > $max) $newprice = $max;
                            if ($newprice < $min) $newprice = $min;
                            break;
                        case 'listing_lower_price':
                            $newprice = ((integer)($listing['result'][0]['price'] - $newprice) <= $min) ? (integer)$min : (integer)($listing['result'][0]['price'] - $newprice);
                            break;
                        case 'listing_raise_price':
                            $newprice = ($listing['result'][0]['price'] + $newprice >= $max) ? (integer)$max : (integer)($listing['result'][0]['price'] + $newprice);
                            break;
                    }
                    $result = $api->setListingCalendar(array("listings" => $listing_obj['result']['_id'], 'from' => $fecha->format('Y-m-d'), "to" => $fecha->format('Y-m-d'), "price" => (integer)$newprice, "note" => $listing['result'][0]['note'] . " (Rule)"));
                    echo ' ' . $listing_obj['result']['title'] . ' (response: ' . $result['status'] . '); </br></br>';

                }

            }
        } catch (Exception $e) {
            echo $e;
            die;
        }
    }

    /**
     * Este es el método que aplica que cambio sobre el departamento en Guety.
     * Solamente se usa para las reglas de tipo WebHook.
     * @param Rule $rule El objeto Regla
     * @param ListingCalendar $calendarlist Listado de los calendarios que serán afectados en la ejecución de la regla.
     * Estos calendarios están almacenados en base de datos.
     * */
    public function dopendingchangepricenow($rule, $calendarlist)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $api = new ApiGuesty();
            $data = array();
            foreach ($calendarlist as $listing) {
                //Buscar si ya se ha ejecutado esta regla en el listing
                $rulelog = $em->getRepository("RestaurantBundle:RuleLog")->findOneBy(array("rule" => $rule->getId(), "checkin" => $listing->getCheckin(), 'listing' => $listing->getListing()));
                //Buscar si este listing aun está vacío
                if (is_null($rulelog)) {
                    $current_calendar = $api->getListingCalendar($listing->getListing(), $listing->getCheckin()->format('Y-m-d'), $listing->getCheckin()->format('Y-m-d'));
                    if ($current_calendar['status'] == 200 && $current_calendar['result'][0]['status'] == Nomenclator::LISTING_AVAILABLE) {
                        //Obtener los valores de máximos y mínimos para cada uno de los deptos implicados en la regla.
                        $listingmaxmin = $rule->getPricesbylisting();
                        $prices = $listingmaxmin[$listing->getListing()];
                        $max = $prices['max'];
                        $min = $prices['min'];
                        $newprice = ($rule->getUnit() == 'euro') ? (integer)($rule->getActionvalue()) : (integer)(($listing->getPrice() * $rule->getActionvalue()) / 100);
                        //Verificar cuál es la acción que se va a realizar en la regla.
                        switch ($rule->getAction()) {
                            case 'listing_change_price':
                                if ($newprice > $max) $newprice = $max;
                                if ($newprice < $min) $newprice = $min;
                                break;
                            case 'listing_lower_price':
                                $newprice = (($listing['result'][0]['price'] - $newprice) <= $min) ? (integer)$min : (integer)($listing->getPrice() - $newprice);

                            case 'listing_raise_price':
                                $newprice = ($listing->getPrice() + $newprice >= $max) ? $max : $listing->getPrice() + $newprice;
                                break;

                        }
                        $result = $api->setListingCalendar(array("listings" => $listing->getListing(), 'from' => $listing->getCheckin()->format('Y-m-d'), "to" => $listing->getCheckin()->format('Y-m-d'), "price" => (integer)$newprice, "note" => "Hook"));
                        //Salvar la traza de la ejecución del la regla
                        if ($result['status'] == 200) {
                            $log = new RuleLog();
                            $log->setCheckin($listing->getCheckin());
                            $log->setRule($rule);
                            $log->setListing($listing->getListing());
                            $log->setOldprice($listing->getPrice());
                            $em->persist($log);
                            $listing->setApplied(true);
                            $em->flush();

                        }
                        $name = $this->getDoctrine()->getRepository("RestaurantBundle:Listing")->findOneBy(array('idguesty' => $listing->getListing()));
                        $data[] = array(
                            "date" => $listing->getCheckin()->format('Y-m-d'),
                            "listing" => $name->getNumber(),
                            "price" => $newprice,
                            "oldprice" => $listing->getPrice(),
                            "status" => $result['status']
                        );
                        echo ' (response: ' . $result['status'] . '); </br></br>';
                    }
                }

            }
            $notifier = $em->getRepository('AdminBundle:Notifier')->findOneBy(array('form' => 'Rules'));
            $mails_array = str_replace(";",',', $notifier->getMails());
            $mail_customer = (new \Swift_Message("Rule execution"))
                ->setBody($this->renderView('RestaurantBundle:Rule:notifyrule.html.twig', array(
                    'data' => $data,
                    "rule" => $rule
                )), "text/html");
            $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
            $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $cabeceras .= "From: The Penthouse <info@log.towerleisure.nl>" . "\r\n";
            mail($mails_array, "Rule execution", $mail_customer->getBody(), $cabeceras);
        } catch (Exception $e) {
            echo $e;
            die;
        }
    }

    /**
     * Obtener el listado de los calendarios implicados en la regla.
     * En este método se verifica si la selección de departamentos es por tipo o por depto en específico.
     * @param Rule $rule El objeto Regla
     * @param Listing $listings Listado de los listings disponibles para renta.
     * @param DateTime $checkin Fecha del calendario que se desea consultar.
     * @return array $calendarlists
     * */
    private function getListingCalendar($rule, $listings, $checkin = null)
    {
        $em = $this->getDoctrine()->getManager();
        //Buscar el Listing Calendar correspondiente
        $api = new ApiGuesty();
        //Si el campo daysahead tiene valor, entonces la se obtiene en la fecha (today + daysahead)
        if (is_null($checkin)) {
            $fecha = (!is_null($rule) && !is_null($rule->getDaysahead())) ? new \DateTime('today + ' . $rule->getDaysahead() . ' days') : new \DateTime();
        } else {
            $fecha = $checkin;
        }
        if ($rule->getBytype() == 1) {
            $calendarlists = array();
            $types = $rule->getTypeofapartment();
            foreach ($listings as $listing) {
                //Si está definido el tipo de depto entonces busco por tipo
                if (is_array($types) && $types[0] != "") {
                    //Verificar el tipo de listing
                    foreach ($types as $item_type) {
                        //si al menos uno de los tipos que se menciona en la regla aparece en el depto
                        if (in_array($item_type, $listing->getType())) {
                            $calendarlists[] = $api->getListingCalendar($listing->getIdguesty(), $fecha->format('Y-m-d'), $fecha->format('Y-m-d'));
                            break;
                        }
                    }
                } //Si no está definido el tipo y está marcado ALL, entonces tomar todos
                else {
                    $calendarlists[] = $api->getListingCalendar($listing->getIdguesty(), $fecha->format('Y-m-d'), $fecha->format('Y-m-d'));
                }
            }
        } else {
            foreach ($rule->getPricesbylisting() as $key => $itemlisting) {
                $calendarlists[] = $api->getListingCalendar($key, $fecha->format('Y-m-d'), $fecha->format('Y-m-d'));
            }
            if (count($rule->getPricesbylisting()) == 0) {
                $listings = $em->getRepository('RestaurantBundle:Listing')->findAll();
                foreach ($listings as $listing) {
                    $calendarlists[] = $api->getListingCalendar($listing->getIdguesty(), $fecha->format('Y-m-d'), $fecha->format('Y-m-d'));
                }
            }
        }
        return $calendarlists;
    }

    /**
     * Eliminar Reglas del sistema.
     *
     * @Route("/{id}/delete/", name="rule_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:Rule')->find($id);
            $user = $this->get('security.token_storage')->getToken()->getUser();

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));
            }

            try {
                $em->remove($entity);
                $em->flush();
                $this->addFlash('success', 'Success! The rule has been removed.');
                return $this->redirect($this->generateUrl('rule_admin'));
            } catch (\Exception $ex) {
                return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
            }
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');
    }


    /**
     * Hook diseñado para la notificación de clientes incluidos en el Black List del sistema.
     *     Está subscrito al hook reservation.new
     * @Route("/rule/hook/reservation/", name="hook_reservation")
     * @Method("POST")
     *
     */
    public function hookReservationAction(Request $request)
    {
        $this->blackList($request);
        $this->ruleReservation($request);
        die;
    }

    /*
     * Método auxiliar para notificar los clientes que acen reservas y que están incluidos en el black list del sistema.
     *
     * */
    private function blackList($request)
    {
        $peticion = (array)json_decode($request->getContent());
        $reservation = (array)($peticion['reservation']);
        $api = new ApiGuesty();
        $guest = $api->guest($reservation['guestId']);
        if ($guest['status'] == 200) {
            $name = $email = null;
            if (isset($guest['result']['email'])) $email = $guest['result']['email'];
            if (isset($guest['result']['fullName'])) $name = $guest['result']['fullName'];
            $em = $this->getDoctrine()->getManager();
            $blacklist = $em->getRepository('RestaurantBundle:BlackList')->findAll();
            $control = '';
            $currentis = false;

            foreach ($blacklist as $element) {
                if (!is_null($email) && $element->getEmail() == $email) {
                    $currentis = true;
                    $control = $element->getDetails();
                } else if (!is_null($name)) {
                    $nameparts = explode($name, " ");
                    foreach ($name as $part){
                        $coincidencia = strpos(strtolower($element->getName()), strtolower($name));
                        if ($coincidencia !== false) {
                            $currentis = true;
                            $control = $element->getDetails();
                            break;
                        }
                    }
                }
                if ($coincidencia) break;
            }
            if ($currentis) {
                $listing = $em->getRepository("RestaurantBundle:Listing")->findOneBy(array("idguesty" => $reservation["listingId"]));
                $notifier = $em->getRepository('AdminBundle:Notifier')->findOneBy(array('form' => 'Rules'));
                $mails_array = explode(';', $notifier->getMails());
                $mail_customer = \Swift_Message::newInstance()
                    ->setFrom('info@log.towerleisure.nl')
                    ->setTo($mails_array)
                    ->setSubject("Warning! Someone in your Black List has a reservation in Guesty")
                    ->setBody($this->renderView('RestaurantBundle:Rule:blacklist.html.twig', array(
                        'data' => array(
                            "name" => $guest['result']['fullName'],
                            "email" => $guest['result']['email'],
                            "listing" => $listing->getNumber(),
                            "checkin" => new\DateTime($reservation['checkIn']),
                            "note" => $control
                        ),
                    )))
                    ->setContentType("text/html");
                $this->get('mailer')->send($mail_customer);
                echo "enviado";
            }
        }
        die;
    }

    /**
     * Método para probar los eventos de los hooks
     *
     * @Route("/rule/hook/test/", name="hook_test")
     * @Method("GET")
     *
     */
    public function hookTestAction()
   {
       //phpinfo();die;
       $data = '{
       "reservation": {
    "_id": "59d0ba61b57d3b04004255e2",
    "lastUpdatedAt": "2019-07-20T15:57:25.426Z",
    "daysInAdvance": 29,
    "listingId": "58a5dffa3798420400c8e691",
    "accountId": "563e0b6a08a2710e00057b82",
    "guestId": "5967610932d1ee1000574c9c",
    "status": "created",
    "confirmationCode": "RU-rAwXPp",
    "checkInDateLocalized": "2019-12-16",
    "checkOutDateLocalized": "2019-12-17",
    "guestsCount": 2,
    "source": "Manual",
    "checkIn": "2019-12-16T13:00:00.000Z",
    "checkOut": "2019-12-17T08:00:00.000Z",
    "nightsCount": 1,
    "confirmedAt": "2017-07-20T09:50:25.372Z",
    "__v": 1,
    "customFields": [],
    "confirmedPreBookings": [],
    "log": [
      {
        "event": "Booking was Canceled successfully",
        "_id": "59d0ba61b57d3b04004255e4",
        "changes": [

        ],
        "at": "2019-07-20T09:50:25.499Z"
      }
    ],
    "pendingTasks": [],
    "review": {
      "shouldReview": true
    },
    "money": {
      "currency": "EUR",
      "hostPayout": 35,
      "guestTotalPrice": 1,
      "alreadyPaid": 0,
      "fareAccommodation": -34,
      "fareCleaning": 35,
      "ownerRevenue": 31.5,
      "commissionIncTax": 3.5,
      "commissionTax": 0,
      "commissionTaxPercentage": 0,
      "commission": 3.5,
      "commissionFormula": "net_income*0.1",
      "netIncome": 35,
      "netIncomeFormula": "host_payout",
      "isFullyPaid": false,
      "balanceDue": 35,
      "paymentsDue": 35,
      "totalPaid": 0,
      "totalRefunded": 0,
      "subTotalPrice": 35,
      "hostPayoutUsd": 1.1817467398561814,
      "autoPaymentsPolicy": [
        {
          "chargeType": "REST_OF_PAYMENT",
          "amount": 0,
          "useGuestCard": true,
          "_id": "59aebb10edad8d0f00d6954c",
          "scheduleTo": {
            "reservationEvent": "CHECK_OUT",
            "timeRelation": {
              "relation": "AT",
              "unit": "HOURS",
              "amount": 1
            }
          }
        }
      ],
      "payments": [
        {
          "policyItemId": "59aebaf3edad8d0f00d68f55",
          "shouldBePaidAt": "2017-10-30T13:00:00.000Z",
          "currency": "EUR",
          "amount": 10.5,
          "paymentMethodId": "589894a91d756b9c47ce1e87",
          "_id": "59d0ba61b57d3b04004255e6",
          "receiptTargets": [],
          "attempts": [],
          "createdAt": "2017-10-01T09:50:25.783Z",
          "refunds": [],
          "status": "PENDING"
        },
        {
          "policyItemId": "59aebb10edad8d0f00d6954c",
          "shouldBePaidAt": "2017-10-31T08:00:00.000Z",
          "currency": "EUR",
          "amount": 24.5,
          "paymentMethodId": "589894a91d756b9c47ce1e87",
          "_id": "59d0ba61b57d3b04004255e5",
          "receiptTargets": [],
          "attempts": [],
          "createdAt": "2017-10-01T09:50:25.782Z",
          "refunds": [],
          "status": "PENDING"
        }
      ],
      "invoiceItems": [
        {
          "title": "Cleaning fee",
          "amount": 35,
          "currency": "EUR",
          "isLocked": true,
          "_id": "59d0ba61b57d3b04004255e3"
        }
      ]
    },
    "integration": {
      "_id": "596b6f5aa6bd32100050be2a",
      "platform": "rentalsUnited",
      "rentalsUnited": {
        "id": 134019397,
        "listingId": 1491473,
        "channelReservationId": null
      },
      "limitations": {
        "availableStatuses": [

        ]
      }
    },
    "createdAt": "2017-10-01T09:50:25.380Z",
    "id": "59d0ba61b57d3b04004255e2"
  },
  "reservationBefore": {
    "_id": "59d0ba61b57d3b04004255e2",
    "lastUpdatedAt": "2017-10-01T14:50:28.254Z",
    "daysInAdvance": 29,
    "accountId": "563e0b6a08a2710e00057b82",
    "listingId": "59ac245d27cb310f0017afe3",
    "guestId": "5967610932d1ee1000574c9c",
    "status": "confirmed",
    "confirmationCode": "RU-rAwXPp",
    "checkInDateLocalized": "2017-10-30",
    "checkOutDateLocalized": "2017-10-31",
    "guestsCount": 2,
    "source": "Manual",
    "checkIn": "2017-10-30T13:00:00.000Z",
    "checkOut": "2017-10-31T08:00:00.000Z",
    "nightsCount": 1,
    "confirmedAt": "2017-10-01T09:50:25.372Z",
    "customFields": [],
    "confirmedPreBookings": [],
    "log": [
      {
        "event": "Booking was confirmed successfully",
        "_id": "59d0ba61b57d3b04004255e4",
        "changes": [

        ],
        "at": "2017-10-01T09:50:25.499Z"
      }
    ],
    "pendingTasks": [],
    "review": {
      "shouldReview": true
    },
    "money": {
      "currency": "EUR",
      "hostPayout": 35,
      "guestTotalPrice": 1,
      "alreadyPaid": 0,
      "fareAccommodation": -34,
      "fareCleaning": 35,
      "ownerRevenue": 31.5,
      "commissionIncTax": 3.5,
      "commissionTax": 0,
      "commissionTaxPercentage": 0,
      "commission": 3.5,
      "commissionFormula": "net_income*0.1",
      "netIncome": 35,
      "netIncomeFormula": "host_payout",
      "isFullyPaid": false,
      "balanceDue": 35,
      "paymentsDue": 35,
      "totalPaid": 0,
      "totalRefunded": 0,
      "subTotalPrice": 35,
      "hostPayoutUsd": 1.1817467398561814,
      "autoPaymentsPolicy": [
        {
          "chargeType": "REST_OF_PAYMENT",
          "amount": 0,
          "useGuestCard": true,
          "_id": "59aebb10edad8d0f00d6954c",
          "scheduleTo": {
            "reservationEvent": "CHECK_OUT",
            "timeRelation": {
              "relation": "AT",
              "unit": "HOURS",
              "amount": 1
            }
          }
        }
      ],
      "payments": [
        {
          "policyItemId": "59aebaf3edad8d0f00d68f55",
          "shouldBePaidAt": "2017-10-30T13:00:00.000Z",
          "currency": "EUR",
          "amount": 10.5,
          "paymentMethodId": "589894a91d756b9c47ce1e87",
          "_id": "59d0ba61b57d3b04004255e6",
          "receiptTargets": [

          ],
          "attempts": [],
          "createdAt": "2017-10-01T09:50:25.783Z",
          "refunds": [],
          "status": "PENDING"
        },
        {
          "policyItemId": "59aebb10edad8d0f00d6954c",
          "shouldBePaidAt": "2017-10-31T08:00:00.000Z",
          "currency": "EUR",
          "amount": 24.5,
          "paymentMethodId": "589894a91d756b9c47ce1e87",
          "_id": "59d0ba61b57d3b04004255e5",
          "receiptTargets": [],
          "attempts": [],
          "createdAt": "2017-10-01T09:50:25.782Z",
          "refunds": [],
          "status": "PENDING"
        }
      ],
      "invoiceItems": [
        {
          "title": "Cleaning fee",
          "amount": 35,
          "currency": "EUR",
          "isLocked": true,
          "_id": "59d0ba61b57d3b04004255e3"
        }
      ]
    },
    "integration": {
      "_id": "596b6f5aa6bd32100050be2a",
      "platform": "rentalsUnited",
      "rentalsUnited": {
        "id": 134019397,
        "listingId": 1491473,
        "channelReservationId": null
      },
      "limitations": {
        "availableStatuses": [

        ]
      }
    },
    "createdAt": "2017-10-01T09:50:25.380Z",
    "id": "59d0ba61b57d3b04004255e2"
  },
  "event": "reservation.updated"
}';
       $ch = curl_init("http://test.log.towerleisure.nl/hook/rollback/");
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
       curl_setopt($ch, CURLOPT_POSTFIELDS, ($data));
       $response = curl_exec($ch);
       var_dump($response);
       die;
   }

    /**
     * Incluir un nuevo hook en Guesty
     *
     * @Route("/rule/hook/create/", name="hook_create")
     * @Method("GET")
     *
     */
    public function hookCreateAction()
    {
        $api = new ApiGuesty();
        $result = $api->createHook("http://log.towerleisure.nl/hook/rollback/", array('reservation.updated'));
        var_dump($result);
        die;
    }

    /**
     * Actualizar un hook en Guesty
     *
     * @Route("/rule/hook/update/", name="hook_update")
     * @Method("GET")
     *
     */
    public function hookUpdateAction()
    {
        $api = new ApiGuesty();
        $result = $api->updateHook("5d3554611e97b9001e96b015", 'http://log.towerleisure.nl/hook/rollback/', array('reservation.updated'));
        var_dump($result);
        die;
    }


    /**
     * Listar los Hooks que están registrados en guesty.
     *
     * @Route("/rule/hook/list/", name="hook_list")
     * @Method("GET")
     * @Template()
     *
     */
    public function hookListAction()
    {
        $api = new ApiGuesty();
        $result = $api->getWebhooks();
        var_dump($result);
        die;
    }


}

