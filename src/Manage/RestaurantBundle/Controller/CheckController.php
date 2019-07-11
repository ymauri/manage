<?php

namespace Manage\RestaurantBundle\Controller;
use Manage\RestaurantBundle\Entity\BlackList;
use Manage\RestaurantBundle\Entity\Checkin;
use Manage\RestaurantBundle\Form\BlackListType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;
use Manage\RestaurantBundle\Controller\ApiGuesty;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CheckController extends Controller {


    /**
     *
     * @Route("/checkin/date/{date}/", name="checkin")
     * @Method("GET")
     * @Template()
     */
    public function checkinAction($date) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN'){
            return $this->render('AdminBundle:Exception:error403.html.twig');
        }
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('RestaurantBundle:Checkin')->findBy(array('date'=>new \DateTime($date), 'status'=>'confirmed'));

        return $this->render('RestaurantBundle:Check:checkinIndex.html.twig', array(
            'entities' => $entities,
            'listing' => $this->getActiveListing(),
        ));
    }

    //Obtener los apartamentos disponibles para las reservas
    private function getActiveListing() {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('RestaurantBundle:Listing')->findBy(array('activeforrent'=>1));
    }

    /**
     *
     * @Route("/checkin/{id}/", name="checkin_show")
     * @Method("GET")
     * @Template()
     */
    public function checkinShowAction($id) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN'){
            return $this->render('AdminBundle:Exception:error403.html.twig');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('RestaurantBundle:Checkin')->findOneBy(array('id'=>$id));
        $listing = $em->getRepository('RestaurantBundle:Listing')->findOneBy(array('id'=>$entity->getListing()));
        return $this->render('RestaurantBundle:Check:checkinShow.html.twig', array(
            'entity' => $entity,
            'listing'=> $listing,
        ));
    }

    /**
     *
     * @Route("/checkout/date/{date}/", name="checkout")
     * @Method("GET")
     * @Template()
     */
    public function checkoutAction($date) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN'){
            return $this->render('AdminBundle:Exception:error403.html.twig');
        }
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('RestaurantBundle:Checkout')->findBy(array('date'=>new \DateTime($date),'status'=>'confirmed'));

        return $this->render('RestaurantBundle:Check:checkoutIndex.html.twig', array(
            'entities' => $entities,
            'listing' => $this->getActiveListing(),
        ));
    }

    /**
     *
     * @Route("/checkout/{id}/", name="checkout_show")
     * @Method("GET")
     * @Template()
     */
    public function checkoutShowAction($id) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN'){
            return $this->render('AdminBundle:Exception:error403.html.twig');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('RestaurantBundle:Checkout')->findOneBy(array('id'=>$id));
        $listing = $em->getRepository('RestaurantBundle:Listing')->findOneBy(array('id'=>$entity->getListing()));
        return $this->render('RestaurantBundle:Check:checkoutShow.html.twig', array(
            'entity' => $entity,
            'listing'=> $listing,
        ));
    }
    
      /**
     *
     * @Route("/pricing/", name="pricing")
     * @Method("GET")
     * @Template()
     */
    public function pricingAction() {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN'){
            return $this->render('AdminBundle:Exception:error403.html.twig');
        }
        $em = $this->getDoctrine()->getManager();
        $listing = $em->getRepository('RestaurantBundle:Listing')->findAll();
        return $this->render('RestaurantBundle:Check:pricing.html.twig', array(
            'listings'=> $listing,
        ));
    }
      /**
     *
     * @Route("/pricingfilter/", name="pricing-filter")
     * @Method("POST")
     * @Template()
     */
    public function pricingFilterAction() {
        $em = $this->getDoctrine()->getManager();
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN'){
            return $this->render('AdminBundle:Exception:error403.html.twig');
        }
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $data = $request->get('data');
            $from = '';
            $to = '';
            $listing = array();
            $response = new JsonResponse();
            
            foreach ($data as $current){
                switch ($current['name']){
                    case 'from':
                        $dated = new \DateTime($current['value']);
                         $from = $dated->format('Y-m-d');
                        break;
                    case 'to':
                        $dated = new \DateTime($current['value']);
                         $to = $dated->format('Y-m-d');
                        break;
                    case 'listing':
                         $listing[] = $current['value'];
                        break;
                        
                }
            }
            
            //hacer consulta guesty
            $api = new ApiGuesty();
            $result = array();
            foreach ($listing as $current){
                $dataguesty = $api->getListingCalendar($current, $from, $to);
                //var_dump($dataguesty);die;
                $listing = $em->getRepository('RestaurantBundle:Listing')->findOneBy(array('idguesty'=>$current));
                if ($dataguesty['status'] == 200 )
                    $result[] =array('listing'=>$listing->getNumber(), 'data'=>$dataguesty['result'], 'idguesty' =>$listing->getIdguesty());
            }
            $response->setData($result);
            return $response;
            
        }
    }
    
    
      /**
     *
     * @Route("/pricingupdate/", name="pricing-update")
     * @Method("POST")
     * @Template()
     */
    public function pricingUpdateAction() {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN'){
            return $this->render('AdminBundle:Exception:error403.html.twig');
        }
        $request = $this->getRequest();
        $res = array();
        $api = new ApiGuesty();
        if ($request->getMethod() == 'POST') {
            $data = $request->get('data');
            foreach ($data as $listing){
                foreach ($listing as $item){
                    if ($item['value'] != "" && $item['value'] != null){
                        $name = explode('plus',$item['name']);
                        $res[] = $api->setListingCalendar(array("listings"=>$name[2], "from"=>$name[1], "to"=>$name[1],  "price"=>$item['value'], "note"=>"Updated from log.towerleisure.nl"));
                    }
                }
            }
            
            $response = new JsonResponse();
           
            $response->setData($res);
            return $response;
            
        }
    }

    /**
     *
     * @Route("/autopricing/", name="autopricing")
     * @Method("GET")
     * @Template()
     */
    public function autopricingAction() {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN'){
            return $this->render('AdminBundle:Exception:error403.html.twig');
        }
        $em = $this->getDoctrine()->getManager();
        $listing = $em->getRepository('RestaurantBundle:Listing')->findAll();
        return $this->render('RestaurantBundle:Check:autopricing.html.twig', array(
            'listings'=> $listing,
        ));
    }

    /**
     *
     * @Route("/autopricingupdate/", name="autopricing-update")
     * @Method("POST")
     * @Template()
     */
    public function autopricingUpdateAction() {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN'){
            return $this->render('AdminBundle:Exception:error403.html.twig');
        }
        $request = $this->getRequest();
        $res = array();
        $api = new ApiGuesty();
        if ($request->getMethod() == 'POST') {
            $data = $request->get('data');
            $listings = array();
            $rules = array();
            foreach ($data as $item){
                if ($item['name'] == 'listing'){
                    $listings[] = $item['value'];
                }
                else {
                    if (($item['name'] == 'condition') &&  $item['value'] != ""){
                        $rules[$item['name']] = array('amount'=>$item['value']);
                    }
                     else if (($item['name'] != 'condition') ){
                         $rules[$item['name']] = $item['value'];
                    }
                }
            }
            //var_dump($rules);die;
            $data = array(
                "pms"   => array(
                    "automation"    => array(
                        "autoPricing"   => array(
                            "active"    => 'false',
                            "rules"     => $rules
                        )
                    )
                )
            );
            $em = $this->getDoctrine()->getManager();
            foreach ($listings as $listing){
                $number_listing = $em->getRepository('RestaurantBundle:Listing')->findOneBy(array('idguesty'=>$listing));
                $res[] = array('data'=> $api->setAutopricing($listing, $data), 'listing'=>$number_listing->getNumber());
            }
            $response = new JsonResponse();
            $response->setData($res);
            return $response;
        }
    }

    /**
     *
     * @Route("/blacklist/", name="blacklist")
     * @Method("GET")
     * @Template()
     */
    public function blackListAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $limit = is_null($request->query->get('limit')) ? 30 : $request->query->get('limit');
            $offset = is_null($request->query->get('offset')) ? 0 : $request->query->get('offset');
            //$entities = $em->getRepository('AdminBundle:RNotifierForm')->findBy(array('form'=>$id),array('date'=>'DESC'), $limit, $offset );
            $entities = $em->getRepository('RestaurantBundle:BlackList')->findBy(array(),array('id'=>'ASC'), $limit, $offset);
            $finlista = $em->getRepository('RestaurantBundle:BlackList')->findBy(array(),array('id'=>'ASC'), 1, $offset + $limit );


            return $this->render('RestaurantBundle:Check:blacklist.html.twig', array(
                'entities' => $entities,
                'page' => $offset/$limit,
                'last' => count($finlista) == 0,
                'first' => $offset == 0
            ));
        }
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }

    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/blacklist/new/", name="blacklist_new")
     * @Method("GET")
     * @Template()
     */
    public function newBlacklistAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new BlackList();
            $form = $this->createCreateForm($entity);
            return $this->render("RestaurantBundle:Check:blacklistnew.html.twig",array(
                'entity' => $entity,
                'form' => $form->createView(),
            ));
        }
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }
    private function createCreateForm(BlackList $entity) {
        $form = $this->createForm(new BlackListType(), $entity, array(
            'action' => $this->generateUrl('blacklist_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    /**
     *
     * @Route("/blacklist/", name="blacklist_create")
     * @Method("POST")
     */
    public function createBlackListAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new BlackList();
            $form = $this->createCreateForm($entity);
            $form->handleRequest($request);
            try {
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($entity);
                    $em->flush();
                    $this->addFlash('success', 'Success! The element has been created.');
                    return $this->redirect($this->generateUrl('blacklist'));
                }
            } catch (\Exception $ex) {
                return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
            }
            $this->addFlash('error', "Error!");
            return $this->render("RestaurantBundle:Check:blacklistnew.html.twig",array(
                'entity' => $entity,
                'form' => $form->createView(),
            ));
        }
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/blacklist/{id}/edit/", name="blacklist_edit")
     * @Template()
     */
    public function editBlackListAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('RestaurantBundle:BlackList')->find($id);

        if (!$entity) {
            return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Client.'));
        }

        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getId() == $id) {

            $form = $this->createForm(new BlackListType(), $entity, array(
                'action' => $this->generateUrl('blacklist_edit', array('id' => $entity->getId())),
                'method' => 'POST',
            ));
            $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary')));
            if ($request->getMethod() == 'POST'){
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $em->flush();
                    $this->addFlash('success', 'Success! The element has been changed.');
                    return $this->redirect($this->generateUrl('blacklist'));
                }
                $this->addFlash('error', $form->getErrorsAsString());
            }

            return $this->render("RestaurantBundle:Check:blacklistedit.html.twig",array(
                'entity' => $entity,
                'form' => $form->createView(),
            ));

        }
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Deletes a Worker entity.
     *
     * @Route("/blacklist/{id}/delete", name="blacklist_delete")
     */
    public function deleteBlackListAction(Request $request, $id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:BlackList')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this element.'));
            }

            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Success! The element has been removed.');
            return $this->redirect($this->generateUrl('blacklist'));
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }

    }


}
