<?php

namespace Manage\RestaurantBundle\Controller;

use Manage\RestaurantBundle\Entity\Folder;
use Manage\RestaurantBundle\Entity\RFolderFolder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\Furniture;
use Manage\RestaurantBundle\Form\FurnitureType;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Furniture controller.
 *
 * @Route("/furniture")
 */
class FurnitureController extends Controller {

    /**
     * Lists all Furniture entities.
     *
     * @Route("/", name="furniture")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $tree = $this->getWholeTree();
        /*$em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('RestaurantBundle:Furniture')->findAll();
        foreach ($entities as $key => $entity){
            $ruta = $this->generatePathFolder($entity->getFolder()->getId());
            $entity->setPathfolder($ruta);
            $em->flush();
            echo $key.'<br/>';
        }
        die;*/
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('RestaurantBundle:Folder')->findAll();

            return $this->render("RestaurantBundle:Furniture:treeview.html.twig", array(
                'entities' => $entities,
                'tree' => json_encode($tree)
                
            ));
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a new Furniture entity.
     *
     * @Route("/", name="furniture_create")
     * @Method("POST")
     * @Template("RestaurantBundle:Furniture:new.html.twig")
     */
    public function createAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new Furniture();
            $form = $this->createCreateForm($entity);
            $originalpath = $form->getData()->getPathimage();
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                    $entity->uploadImage($this->container->getParameter('images.furniture'));
                $entity->setPathfolder($this->generatePathFolder($entity->getFolder()->getId()));
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Success! The furniture has been created.');
                return $this->redirect($this->generateUrl('furniture'));
            }
            $this->addFlash('error', 'Error! There was an error. Try again!');
            return $this->redirect($this->generateUrl('furniture'));
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    private function generatePathFolder($folder){
        $result = "/".$folder;
        $em = $this->getDoctrine()->getManager();
        $object = $em->getRepository("RestaurantBundle:RFolderFolder")->findOneBy(array('child'=>$folder));
        if ($object->getFather()->getIsroot()){
            $result = $result.'/'.$object->getFather()->getId().'/';
            return $result;
        }
        else {
            return $result.$this->generatePathFolder($object->getFather()->getId());
        }
    }

    private function getChildTree($parent){
        $result = array();
        $em = $this->getDoctrine()->getManager();
        $objects = $em->getRepository("RestaurantBundle:Folder")->getChildrensNodes($parent);
        foreach ($objects as $key => $object ){
            $result[$key]['text'] = $object->getDetails();
            $result[$key]['id'] = $object->getId();
            $result[$key]['children'] = $this->getChildTree($object->getId());
        }
        return $result;
    }


    private function getWholeTree(){
        $result = array();
        $em = $this->getDoctrine()->getManager();
        $objects = $em->getRepository("RestaurantBundle:Folder")->findBy(array('isroot'=>true), array('details'=>'ASC'));
        foreach ($objects as $key => $object ){
            $result[$key]['text'] = $object->getDetails();
            $result[$key]['id'] = $object->getId();
            $result[$key]['children'] = $this->getChildTree($object->getId());
            $result[$key]['state'] = array("opened"=> true);
        }
        return $result;
    }


    /**
     * Creates a form to create a Furniture entity.
     *
     * @param Furniture $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Furniture $entity) {
        $form = $this->createForm(new FurnitureType(), $entity, array(
            'action' => $this->generateUrl('furniture_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class'=>'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Furniture entity.
     *
     * @Route("/new/{parent}", name="furniture_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($parent) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new Furniture();
            $form = $this->createCreateForm($entity);

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
                'parent' => $parent,
            );
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Finds and displays a Furniture entity.
     *
     * @Route("/{id}/", name="furniture_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:Furniture')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $form = $this->createEditForm($entity);

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
                'image' => file_exists($this->container->getParameter('images.furniture').'/'.$entity->getPathimage()) ? $entity->getPathimage() : false,

            );
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Displays a form to edit an existing Furniture entity.
     *
     * @Route("/{id}/edit/", name="furniture_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:Furniture')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity' => $entity,
                'form' => $editForm->createView(),
                'image' => file_exists($this->container->getParameter('images.furniture').'/'.$entity->getPathimage()) ? $entity->getPathimage() : false,
                'delete_form' => $deleteForm->createView(),
            );
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a form to edit a Furniture entity.
     *
     * @param Furniture $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Furniture $entity) {
        $form = $this->createForm(new FurnitureType(), $entity, array(
            'action' => $this->generateUrl('furniture_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update','attr' => array('class'=>'btn btn-primary')));

        return $form;
    }

    /**
     * Edits an existing Furniture entity.
     *
     * @Route("/{id}/", name="furniture_update")
     * @Method("PUT")
     * @Template("RestaurantBundle:Furniture:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:Furniture')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createEditForm($entity);
            $originalpath = $editForm->getData()->getPathimage();
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                if (null == $entity->getImage()){
                    $entity->setPathimage($originalpath);
                } else{
                    $entity->uploadImage($this->container->getParameter('images.furniture'));
                    if ($originalpath != '') unlink($this->container->getParameter('images.furniture').'/'.$originalpath);
                }
                $entity->setPathfolder($this->generatePathFolder($entity->getFolder()->getId()));
                $em->flush();
                $this->addFlash('success', 'Success! The furniture has been changed.');
                return $this->redirect($this->generateUrl('furniture'));
            }
            
            return array(
                'entity' => $entity,
                'form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Deletes a Furniture entity.
     *
     * @Route("/{id}/delete/", name="furniture_delete")
     * @Method("GET")
     */
    public function deleteAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:Furniture')->find($id);
            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));
            }
            if (file_exists($this->container->getParameter('images.furniture').'/'.$entity->getPathimage())) unlink($this->container->getParameter('images.furniture').'/'.$entity->getPathimage());
            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Success! The furniture has been removed.');

            return $this->redirect($this->generateUrl('furniture'));
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a form to delete a Furniture entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('furniture_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    /**
     * @Route("/furniture/treeload", name="furniture_treeload")
     * @Method("GET")
     *      */
    public function treeloadAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $parent = $request->get('parent');
        $json = new JsonResponse();
        $data = array();
        if ($parent == "#"){
            $nodes = $em->getRepository("RestaurantBundle:Folder")->getRootNodes();
            //var_dump($nodes);die;
            foreach ($nodes as $key => $node){
                $data[] = array(
                    "id" => $node->getId(),
                    "text" => $node->getDetails(),
                    "icon" => "fa fa-folder icon-lg icon-state-success",
                    "children" => !$node->getIssheet(),
                    "type" => "root"
                );
            }
        } else{
            $nodes = $em->getRepository("RestaurantBundle:Folder")->getChildrensNodes($parent);
            foreach ($nodes as $key => $node){
                $data[] = array(
                    "id" => $node->getId(),
                    "text" => $node->getDetails(),
                    "icon" => "fa fa-folder icon-lg icon-state-success",
                    "children" => !$node->getIssheet(),
                );
            }
        }
        $json->setData(
            $data
        );
        return $json;
    }

    /**
     * @Route("/furniture/editnode", name="furniture_editnode")
     * @Method("GET")
     *      */
    public function editNodeAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $name = $request->get('name');
        $parent = $request->get('parent');
        $old = $request->get('old');
        $json = new JsonResponse();
        if (is_numeric($id)){
            $node = $em->getRepository("RestaurantBundle:Folder")->find($id);
            $node->setDetails($name);
            $em->persist($node);
            $em->flush();
            $json->setData(array(
                "action" => "update",
                "id" => $id
            ));

        }
        else {
            $node = new Folder();
            $node->setDetails($name);
            $node->setIsroot(!is_numeric($parent));
            $node->setIssheet(true);
            $em->persist($node);
            $em->flush();

            if (!is_numeric($parent)){
                $parent = explode("_", $parent);
                $parent = $parent[1];
            }
            //if (is_numeric($parent)){
                $relation = new RFolderFolder();
                $folderparent = $em->getRepository("RestaurantBundle:Folder")->find($parent);
                $folderparent->setIssheet(false);
                $relation->setFather($folderparent);
                $relation->setChild($node);
                $em->persist($relation);
            //}
            $em->flush();
            $json->setData(array(
                "action" => "new",
                "id" => $node->getId()
            ));

        }
        return $json;
    }


    /**
     * @Route("/furniture/deletenode", name="furniture_deletenode")
     * @Method("GET")
     *
     */
    public function deleteNodeAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $parent = $request->get('parent');
        $json = new JsonResponse();
        $childrens = $em->getRepository("RestaurantBundle:RFolderFolder")->findBy(array('father'=>$id));
        $node = $em->getRepository("RestaurantBundle:Folder")->find($id);
        foreach ($childrens as $children) {
            $em->remove($children->getChild());
        }
        $em->remove($node);
        $em->flush();
        $json->setData(true);
        return $json;
    }
    /**
     * @Route("/furniture/tablecontent", name="furniture_tablecontent")
     * @Method("GET")
     *
     */
    public function tableContentAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $entities = $em->getRepository("RestaurantBundle:Folder")->getChildrensFurnitures($id);
        if (count($entities) > 0){
            echo $this->renderView("RestaurantBundle:Furniture:datatable.html.twig", array('entities'=>$entities));die;
        }
        else {
            echo "<i>Empty folder. Select another one.</i>"; die;
        } 
            

    }

    /**
     * @Route("/move/", name="furniture_move")
     * @Method("POST")
     *
     */
    public function moveAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $childrens = $request->get('childrens');
        $parent_id = $request->get('parent');
        $parent = $em->getRepository("RestaurantBundle:Folder")->find($parent_id);
        if (!is_null($parent)){
            foreach ($childrens as $child) {
                $furniture = $em->getRepository("RestaurantBundle:Furniture")->find($child);
                if (!is_null($furniture)) {
                    $furniture->setFolder($parent);
                    $path = $this->generatePathFolder($parent_id);
                    $furniture->setPathfolder($path);
                    $em->persist($furniture);
                }
            }
            $em->flush();
        }
        $json = new JsonResponse();
        $json->setData(true);
        $this->addFlash('success', 'Success! The furniture has been moved to '.$parent->getDetails());

        return $json;
    }

}

