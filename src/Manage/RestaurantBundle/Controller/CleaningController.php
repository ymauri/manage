<?php

namespace Manage\RestaurantBundle\Controller;

use Manage\RestaurantBundle\Entity\CleaningLog;
use Symfony\Bridge\Monolog\Handler\FingersCrossed\NotFoundActivationStrategy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\Hotel;
use Manage\RestaurantBundle\Form\HotelType;
use Manage\RestaurantBundle\Entity\RCheckoutHotel;
use Manage\RestaurantBundle\Entity\RCheckinHotel;
use Manage\RestaurantBundle\Entity\Cleaning;
use Manage\RestaurantBundle\Entity\CleaningExtra;
use Manage\RestaurantBundle\Form\CleaningExtraType;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Hotel controller.
 *
 * @Route("/cleaning")
 */
class CleaningController extends Controller
{
    /**
     * Tarea programada para cada mañana actualiza los estados de los departamentos.
     *
     * @Route("/status/", name="cleaning_status")
     * @Method("GET")
     * @Template()
     *
     * */
    public function statusAction()
    {
        $api = new ApiGuesty();
        $result = $api->getCleaningStatus();
        if ($result["status"] == 200) {
            $apilistings = $result["result"]["results"];
            $em = $this->getDoctrine()->getManager();
            $objlistings = $em->getRepository("RestaurantBundle:Listing")->findAll();
            foreach ($objlistings as $objlisting) {
                foreach ($apilistings as $apilisting) {
                    if ($apilisting["_id"] == $objlisting->getIdguesty()) {
                        echo $objlisting->getNumber() . "   " . $apilisting["cleaningStatus"]["value"] . "  " . date("H:i:s") . "<br/>";
                        $objlisting->setStatus($apilisting["cleaningStatus"]["value"]);
                        $objlisting->setUpdatedat(new \DateTime("now"));
                    }
                }
                $em->persist($objlisting);
            }
            $em->flush();
        }
        die("done");
    }

    /**
     *
     * @Route("/{date}/", name="cleaning")
     * @Method("GET")
     * @Template()
     */
    public function cleaningAction($date)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $cleanfirst = array();
        $cleanlater = array();
        $extraarray = array();
        $today = new \DateTime("today");
        $date = new \DateTime($date);

        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_RECEPTION' || $user->getRole() == 'ROLE_BASIC') {
            $em = $this->getDoctrine()->getManager();
            $cleaninids = array();
            $default = false;
            //Si la fecha es menor o igual que la fecha de hoy se listan los items de limpieza
            //Los cleanings puede ser modificados solo el día de hoy. El resto de los días no tiene sentido.
            if ($date <= $today) {
                $hotel = $em->getRepository('RestaurantBundle:Hotel')->findOneBy(array('dated' => $date));
                $cleanings = $em->getRepository("RestaurantBundle:Cleaning")->findBy(array("dated" => $date), array("listing" => "DESC"));
                $cleaningextra = $em->getRepository("RestaurantBundle:CleaningExtra")->cleaningByDateRange($date->format("Y-m-d"), $date->format('w'));
                //Si hay registros de limpieza y existe el formulario hotel
                if (count($cleanings) > 0 && !is_null($hotel)) {
                    $relationscheckin = $em->getRepository('RestaurantBundle:RCheckinHotel')->findBy(array('hotel' => $hotel->getId()), array("listing" => "DESC"));
                    //$cleanings = $em->getRepository("RestaurantBundle:Cleaning")->findBy(array("dated" => $hotel->getDated()));
                    //Buscar si hay alguna limpieza programada
                    foreach ($cleanings as $cleaning) {
                        $later = false;
                        $cleaninids [] = $cleaning->getListing()->getId();
                        foreach ($relationscheckin as $checkin) {
                            if ($cleaning->getListing()->getId() == $checkin->getListing()->getId() && !$cleaning->getIsextra()) {
                                $cleanfirst[] = $cleaning;
                                $later = true;
                                break;
                            }
                        }
                        if (!$later && !$cleaning->getIsextra()) $cleanlater[] = $cleaning;
                        if (count($cleaningextra) > 0 && in_array($cleaning->getListing()->getId(), $cleaningextra)) {
                            $cleaning->setIsextra(true);
                            $em->persist($cleaning);
                            $extraarray[] = $cleaning;
                        }
                    }
                }//Si existe el formulario hotel y no hay registros de limpieza
                //Si no existen registros de limpieza y existe el formulario hotel
                else if (count($cleanings) == 0 && !is_null($hotel)) {
                    //Crear los cleanings
                    $relationscheckout = $em->getRepository('RestaurantBundle:RCheckoutHotel')->findBy(array('hotel' => $hotel->getId()), array("listing" => "DESC"));
                    $relationscheckin = $em->getRepository('RestaurantBundle:RCheckinHotel')->findBy(array('hotel' => $hotel->getId()), array("listing" => "DESC"));
                    foreach ($relationscheckout as $checkout) {
                        $later = false;
                        $cleaning = $em->getRepository("RestaurantBundle:Cleaning")->findOneBy(array("listing" => $checkout->getListing(), "dated" => $date));
                        if (is_null($cleaning)) {
                            $cleaning = new Cleaning();
                            $cleaning->setIsextra(false);
                            $cleaning->setDated($date);
                            $cleaning->setStatus($checkout->getCheckoutdone() ? Nomenclator::LISTING_CHECKEDOUT : Nomenclator::LISTING_DIRTY);
                            $cleaning->setCheckout($checkout);
                            $cleaning->setListing($checkout->getListing());
                            $em->persist($cleaning);
                        }
                        $cleaninids [] = $checkout->getListing()->getId();
                        foreach ($relationscheckin as $checkin) {
                            if ($checkout->getListing()->getId() == $checkin->getListing()->getId()) {
                                $cleanfirst[] = $cleaning;
                                $later = true;
                                break;
                            }
                        }
                        if (!$later) $cleanlater[] = $cleaning;

                    }
                }
                //Si no hay registro de limpieza y no exite hotel
                else if (count($cleanings) == 0 && is_null($hotel)) {
                    $relationscheckout = $em->getRepository('RestaurantBundle:Checkout')->findBy(array('date' => $date,  'status'=>'confirmed'), array("listing" => "DESC"));
                    foreach ($relationscheckout as $checkout) {
                        //$later = false;
                        $cleaning = $em->getRepository("RestaurantBundle:Cleaning")->findOneBy(array("listing" => $checkout->getListing(), "dated" => $date));
                        if (is_null($cleaning)) {
                            $cleaning = new Cleaning();
                            $cleaning->setIsextra(false);
                            $cleaning->setDated($date);
                            $cleaning->setStatus(Nomenclator::LISTING_DIRTY);
                            $cleaning->setCheckout(NULL);
                            $cleaning->setListing($em->find("RestaurantBundle:Listing", $checkout->getListing()));
                            $em->persist($cleaning);
                            $cleaninglog = new CleaningLog();
                            $cleaninglog->setCleaning($cleaning);
                            $cleaninglog->setStatus(Nomenclator::LISTING_DIRTY);
                            $cleaninglog->setUpdatedat(new \DateTime("now"));
                            $em->persist($cleaninglog);
                        }
                        $cleaninids [] = $cleaning->getListing()->getId();
                        $cleanlater[] = $cleaning;
                    }
                    $cleanfirst = NULL;

                } else {
                    $cleanfirst = NULL;
                    foreach ($cleanings as $cleaning){
                        if (!$cleaning->getIsextra())
                            $cleanlater[] = $cleaning;
                        else $extraarray[] = $cleaning;
                    }
                    $default = true;
                }
                if (count($cleaningextra) > 0 && !$default) {
                    foreach ($cleaningextra as $extra) {
                        if (!in_array($extra, $cleaninids)) {
                            $cleaningnew = new Cleaning();
                            $cleaningnew->setStatus(Nomenclator::LISTING_DIRTY);
                            $cleaningnew->setCheckout(null);
                            $cleaningnew->setDated($date);
                            $cleaningnew->setIsextra(true);
                            $listing = $em->find("RestaurantBundle:Listing", $extra);
                            $cleaningnew->setListing($listing);
                            $em->persist($cleaningnew);

                            $cleaninglog = new CleaningLog();
                            $cleaninglog->setCleaning($cleaningnew);
                            $cleaninglog->setStatus(Nomenclator::LISTING_DIRTY);
                            $cleaninglog->setUpdatedat(new \DateTime("now"));
                            $em->persist($cleaninglog);
                            $extraarray[] = $cleaningnew;
                        }
                    }
                }
                $em->flush();
            }
        }
        return $this->render('RestaurantBundle:Cleaning:cleaningindex.html.twig', array(
            'cleanfirst' => $cleanfirst,
            'cleanlater' => $cleanlater,
            'cleanprog' => $extraarray,
            "dated" => $date > $today ? NULL : $date->format("d-m-Y"),
        ));
    }

    /**
     * Hook diseñado para la actualización del estado de la limpieza de los deptos.
     * Está subscrito al hook listing.updated
     * @Route("/updatelisting/", name="update_listing")
     * @Method("POST")
     *
     */
    public function hookUpdateListingAction(Request $request){
        $peticion = (array)json_decode($request->getContent());
        $texto = var_dump($peticion);
        mail("ymauri@gmail.com", $texto);
        die;
    }


    /**
     *
     * @Route("/extra/update/", name="cleaning_update_status")
     * @Method("POST")
     * @Template()
     */
    public function cleaningUpdateAction()
    {

        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $data = $request->get('data');
        $dated = new \DateTime($data[2]['value']);
        $today = new \DateTime("today");
        if ($today == $dated){

            //$relation = $em->find('RestaurantBundle:RCheckoutHotel', $data[0]['value']);
            $cleaning = $em->find("RestaurantBundle:Cleaning", $data[0]['value']);
            $status = "";
            //Si el estado es dirty o checkedOut, entonces pasar a working
            if (!is_null($cleaning) && ($cleaning->getStatus() == Nomenclator::LISTING_DIRTY || $cleaning->getStatus() == Nomenclator::LISTING_CHECKEDOUT)) {
                $status = Nomenclator::LISTING_WORKING;
            } //Si el estado es working, entonces pasar a Clean
            else if (!is_null($cleaning) && $cleaning->getStatus() == Nomenclator::LISTING_WORKING) {
                $status = Nomenclator::LISTING_CLEAN;
            } //Si el estado es clean, entonces pasar a dirty o checkedOut
            else if (!is_null($cleaning) && $cleaning->getStatus() == Nomenclator::LISTING_CLEAN) {
                if (is_null($cleaning->getCheckout())){
                    $status = Nomenclator::LISTING_DIRTY;
                }
                else{
                    $relation = $em->find("RestaurantBundle:RCheckoutHotel", $cleaning->getCheckout()->getId());
                    $status = $relation->getCheckoutdone() ? Nomenclator::LISTING_CHECKEDOUT : Nomenclator::LISTING_DIRTY;
                }
            }
            if ($status != "") {
                $cleaning->setStatus($status);
                //Buscar si hay un status registrado
                $logold = $em->getRepository("RestaurantBundle:CleaningLog")->findOneBy(array("cleaning"=>$cleaning, "status"=>$status));
                if (is_null($logold)){
                    $cleaninglog = new CleaningLog();
                    $cleaninglog->setStatus($status);
                    $cleaninglog->setUpdatedat(new \DateTime());
                    $cleaninglog->setCleaning($cleaning);
                    $em->persist($cleaninglog);
                }
                else if ($status == Nomenclator::LISTING_DIRTY || $status == Nomenclator::LISTING_CHECKEDOUT) {
                    $working = $em->getRepository("RestaurantBundle:CleaningLog")->findOneBy(array("cleaning"=>$cleaning, "status"=>Nomenclator::LISTING_WORKING));
                    $clean = $em->getRepository("RestaurantBundle:CleaningLog")->findOneBy(array("cleaning"=>$cleaning, "status"=>Nomenclator::LISTING_CLEAN));
                    $em->remove($working);
                    $em->remove($clean);
                }
                else {
                    $logold->setStatus($status);
                    $logold->setUpdatedat(new \DateTime());
                    $em->persist($logold);
                }

                $em->flush();
                //Actualizar los valores en guesty solo si la limpieza es del día de hoy
                $today = new \DateTime("today");
                if ($cleaning->getDated()->format("Y-m-d") == $today->format("Y-m-d")) {
                    $api = new ApiGuesty();
                    $result = $api->setCleaningStatus($cleaning->getListing()->getIdguesty(), $status == Nomenclator::LISTING_CLEAN ? Nomenclator::LISTING_CLEAN : Nomenclator::LISTING_DIRTY);
                    if ($result["status"] != 200) echo "false";
                    die;
                }
                echo "true";
                die;
            }    
        }
         else echo "Out of date";
        die;
    }

    /**
     * Lists all CleaningExtra entities.
     *
     * @Route("/logs/{date}/", name="cleaninglog")
     * @Method("GET")
     * @Template("RestaurantBundle:Cleaning:logs.html.twig")
     */
    public function logsAction($date, Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $cleaning = $em->getRepository("RestaurantBundle:Cleaning")->findBy(array("dated" => new \DateTime($date)));
            $cleaningids = array();
            foreach ($cleaning as $clean) {
                $cleaningids[] = $clean->getId();
            }
            $logs = $this->getDoctrine()
                ->getRepository('RestaurantBundle:CleaningLog');
            $entities = $logs->createQueryBuilder('l')
                ->innerJoin('l.cleaning', 'c')
                ->where('c.dated = :dated')
                ->setParameter('dated', new \DateTime($date))
                ->orderBy("l.cleaning", "ASC")
                ->addOrderBy("l.updatedat", "ASC")
                //->setFirstResult($offset)
                //->setMaxResults($limit)
                ->getQuery()->getResult();

            //$entities = $em->getRepository('RestaurantBundle:CleaningLog')->findBy(array(),array("cleaning"=>"ASC","updatedat"=>"ASC"), $limit, $offset );
            //$finlista = $em->getRepository('RestaurantBundle:CleaningLog')->findBy(array(),array(), 1, $offset + $limit  );
            $finlista = $em->getRepository('RestaurantBundle:CleaningLog')->findBy(array(), array(), 1);
            return array(
                'entities' => $entities,
                'logdate' => $date,
                //'page' => $offset/$limit,
                //'last' => count($finlista) == 0,
                //'first' => $offset == 0
            );
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }


    /**
     * Lists all CleaningExtra entities.
     *
     * @Route("/", name="cleaningextra")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $limit = is_null($request->query->get('limit')) ? 30 : $request->query->get('limit');
        $offset = is_null($request->query->get('offset')) ? 0 : $request->query->get('offset');
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('RestaurantBundle:CleaningExtra')->findBy(array(), array(), $limit, $offset);
            $finlista = $em->getRepository('RestaurantBundle:CleaningExtra')->findBy(array(), array(), 1, $offset + $limit);
            return array(
                'entities' => $entities,
                'page' => $offset / $limit,
                'last' => count($finlista) == 0,
                'first' => $offset == 0
            );
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a new CleaningExtra entity.
     *
     * @Route("/", name="cleaningextra_create")
     * @Method("POST")
     * @Template("RestaurantBundle:Cleaning:edit.html.twig")
     */
    public function createAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new CleaningExtra();
            $form = $this->createCreateForm($entity);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Success! The cleaningextra has been created.');
                return $this->redirect($this->generateUrl('cleaningextra_show', array('id' => $entity->getId())));
            }

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a form to create a CleaningExtra entity.
     *
     * @param CleaningExtra $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CleaningExtra $entity)
    {
        $form = $this->createForm(new CleaningExtraType(), $entity, array(
            'action' => $this->generateUrl('cleaningextra_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new CleaningExtra entity.
     *
     * @Route("/extra/new", name="cleaningextra_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new CleaningExtra();
            $form = $this->createCreateForm($entity);

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Finds and displays a CleaningExtra entity.
     *
     * @Route("/extra/{id}/", name="cleaningextra_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:CleaningExtra')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity' => $entity,
                'form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Displays a form to edit an existing CleaningExtra entity.
     *
     * @Route("/extra/{id}/edit/", name="cleaningextra_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:CleaningExtra')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity' => $entity,
                'form' => $editForm->createView(),
            );
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a form to edit a CleaningExtra entity.
     *
     * @param CleaningExtra $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CleaningExtra $entity)
    {
        $form = $this->createForm(new CleaningExtraType(), $entity, array(
            'action' => $this->generateUrl('cleaningextra_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Edits an existing CleaningExtra entity.
     *
     * @Route("/extra/{id}/", name="cleaningextra_update")
     * @Method("PUT")
     * @Template("RestaurantBundle:Cleaning:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:CleaningExtra')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createEditForm($entity);
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $em->flush();
                $this->addFlash('success', 'Success! The cleaningextra has been changed.');
                return $this->redirect($this->generateUrl('cleaningextra_show', array('id' => $id)));
            }

            return array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Deletes a CleaningExtra entity.
     *
     * @Route("/extra/{id}/delete", name="cleaningextra_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:CleaningExtra')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this CleaningExtra.'));
            }

            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Success! The cleaningextra has been removed.');
            return $this->redirect($this->generateUrl('cleaningextra'));
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }

    }

    /**
     * Creates a form to delete a CleaningExtra entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cleaningextra_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm();
    }

    /**
     * See the cleaning log per month.
     *
     * @Route("/summary/{month}/", name="cleaning_summary")
     * @Method("GET")
     * @Template("RestaurantBundle:Cleaning:monthlogs.html.twig")
     */
    public function summaryAction(Request $request, $month)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $result = array();
            $resultextra = array();
            for ($i = 1; $i <= 31; $i++){
                $date = new \DateTime($i."-".$month);
                //Si existe la fecha y el día es menor al día de hoy
                if (checkdate((integer)$date->format("m"), $date->format("d"), (integer)$date->format("Y")) && strtotime($date->format("d-m-Y")) < strtotime(date("d-m-Y"))){
                    $cleanings = $em->getRepository("RestaurantBundle:Cleaning")->findBy(array("dated"=> $date, "isextra"=>0));
                    if (!is_null($cleanings)){
                        $totalhoras = 0;
                        $deptos = array();
                        foreach ($cleanings as $cleaning){
                            //var_dump($cleaning);die;
                            $clean = $em->getRepository("RestaurantBundle:CleaningLog")->findOneBy(array("cleaning"=> $cleaning, "status"=>Nomenclator::LISTING_CLEAN));
                            $working = $em->getRepository("RestaurantBundle:CleaningLog")->findOneBy(array("cleaning"=> $cleaning, "status"=>Nomenclator::LISTING_WORKING));
                            if (!is_null($clean) && !is_null($working)){
                                $tiempo = $working->getUpdatedat()->diff($clean->getUpdatedat());
                                if ($tiempo->i > 0){
                                    $totalhoras += $tiempo->i;
                                }
                                $deptos[] = $cleaning->getListing()->getNumber();
                            }
                        }
                        if (count($deptos) > 0) {
                            $result[] = array(
                                "day" => $i,
                                "minutes" => $totalhoras,
                                "deptos" => implode(", ", $deptos),
                                "numberdptos" => count($deptos),
                            );
                        }
                    }
                    $cleanings = $em->getRepository("RestaurantBundle:Cleaning")->findBy(array("dated"=> $date, "isextra"=>1));
                    if (!is_null($cleanings)){
                        $totalhoras = 0;
                        $deptos = array();
                        foreach ($cleanings as $cleaning){
                            //var_dump($cleaning);die;
                            $clean = $em->getRepository("RestaurantBundle:CleaningLog")->findOneBy(array("cleaning"=> $cleaning, "status"=>Nomenclator::LISTING_CLEAN));
                            $working = $em->getRepository("RestaurantBundle:CleaningLog")->findOneBy(array("cleaning"=> $cleaning, "status"=>Nomenclator::LISTING_WORKING));
                            if (!is_null($clean) && !is_null($working)){
                                $tiempo = $working->getUpdatedat()->diff($clean->getUpdatedat());
                                if ($tiempo->i > 0){
                                    $totalhoras += $tiempo->i;
                                }
                                $deptos[] = $cleaning->getListing()->getNumber();
                            }
                        }
                        if (count($deptos) > 0){
                            $resultextra[] = array (
                                "day"           => $i,
                                "minutes"       => $totalhoras,
                                "deptos"        => implode(", ", $deptos),
                                "numberdptos"   => count($deptos),
                            );
                        }

                    }
                    
                }
            }
            
            return  array('entities' => $result, "extra" => $resultextra, "dated" => new \DateTime("1-".$month));

        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }

    }


}
