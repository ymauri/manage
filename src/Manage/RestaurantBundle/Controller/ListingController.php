<?php

namespace Manage\RestaurantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\Listing;
use Manage\RestaurantBundle\Form\ListingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Listing controller.
 *
 * @Route("/listing")
 */
class ListingController extends Controller {

    /**
     * Lists all Listing entities.
     *
     * @Route("/", name="listing")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('RestaurantBundle:Listing')->findAll();

            return array(
                'entities' => $entities,
            );

    }

    /**
     * Creates a new Listing entity.
     *
     * @Route("/", name="listing_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Template("RestaurantBundle:Listing:edit.html.twig")
     */
    public function createAction(Request $request) {
            $entity = new Listing();
            $form = $this->createCreateForm($entity);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $entity->uploadImage($this->container->getParameter('images.listing'));
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Success! The listing has been created.');
                return $this->redirect($this->generateUrl('listing_show', array('id' => $entity->getId())));
            }

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );

    }

    /**
     * Creates a form to create a Listing entity.
     *
     * @param Listing $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Listing $entity) {
        $form = $this->createForm(new ListingType(), $entity, array(
            'action' => $this->generateUrl('listing_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class'=>'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Listing entity.
     *
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/new/", name="listing_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
            $entity = new Listing();
            $form = $this->createCreateForm($entity);

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );

    }

    /**
     * Finds and displays a Listing entity.
     *
     * @Route("/{id}/", name="listing_show")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:Listing')->find($id);
            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));
            }
            $deleteForm = $this->createEditForm($entity);
            //$furniture = $em->getRepository('RestaurantBundle:Furniture')->findBy(array('location'=>$entity->getId()));
            return array(
                'entity' => $entity,
                'delete_form' => $deleteForm->createView(),
                'furnitures' => null,
            );

    }

    /**
     * Displays a form to edit an existing Listing entity.
     *
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/{id}/edit/", name="listing_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:Listing')->find($id);

            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);
           // $furniture = $em->getRepository('RestaurantBundle:Furniture')->findBy(array('location'=>$entity->getId()));

            return array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'furnitures' => null,//$furniture,
                'delete_form' => $deleteForm->createView(),
            );

    }

    /**
     * Creates a form to edit a Listing entity.
     *
     * @param Listing $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Listing $entity) {
        $form = $this->createForm(new ListingType(), $entity, array(
            'action' => $this->generateUrl('listing_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update','attr' => array('class'=>'btn btn-primary')));

        return $form;
    }

    /**
     * Edits an existing Listing entity.
     *
     * @Route("/{id}/", name="listing_update")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Method("PUT")
     * @Template("RestaurantBundle:Listing:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:Listing')->find($id);

            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createEditForm($entity);
            $originalpath = $editForm->getData()->getPathimage();
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                if (null == $entity->getImage()){
                    $entity->setPathimage($originalpath);
                } else{
                    $entity->uploadImage($this->container->getParameter('images.listing'));
                    if ($originalpath != '') unlink($this->container->getParameter('images.listing').'/'.$originalpath);
                }
                $activerules = $em->getRepository('RestaurantBundle:Rule')->findBy(array('active'=>1));
                foreach ($activerules as $rule){
                    //Actualizar los valores de máximos y mínimos para los deptos de las reglas.
                    $listings = $em->getRepository('RestaurantBundle:Listing')->findAll();
                    $arraylistings = array();
                    foreach ($listings as $listing){
                        $arraylistings[$listing->getIdguesty()]['min'] = $listing->getMinprice();
                        $arraylistings[$listing->getIdguesty()]['max'] = $listing->getMaxprice();
                    }
                    $rule->setPricesbylisting($arraylistings);
                    $settings = $em->getRepository('RestaurantBundle:Parameters')->getFieldsRules();
                    $arraysettings = array();
                    foreach ($settings as $setting){
                        $arraysettings[$setting->getVariable()] = $setting->getValue();
                    }
                    $rule->setPricesbytype($arraysettings);
                }

                $em->flush();
                $this->addFlash('success', 'Success! The listing has been changed.');
                return $this->redirect($this->generateUrl('listing_show', array('id' => $id)));
            }

            return array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );

    }

    /**
     * Deletes a Listing entity.
     *
     * @Route("/{id}/delete/", name="listing_delete")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Method("GET")
     */
    public function deleteAction($id) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:Listing')->find($id);
            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));
            }
            //if ($entity->getPathimage() != '') unlink($this->container->getParameter('images.furniture').'/'.$entity->getPathimage());
            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Success! The listing has been removed.');

            return $this->redirect($this->generateUrl('listing'));

    }

    /**
     * Creates a form to delete a Listing entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('listing_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    /**
     * Deletes a Listing entity.
     *
     * @Route("/{id}/priority/", name="listing_priority")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Method("POST")
     */
    public function orderAction($id, Request $request) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:Listing')->find($id);
            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));
            }

            if ($request->get('priority') > 0){
                $entity->setPriority ((integer)$request->get('priority'));
                $em->flush();
            }

            $this->addFlash('success', 'Success! The listing has been updated.');

            return $this->redirect($this->generateUrl('listing'));
        
    }
}
