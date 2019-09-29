<?php

namespace Manage\RestaurantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\Source;
use Manage\RestaurantBundle\Form\SourceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Source controller.
 *
 * @Route("/source")
 */
class SourceController extends Controller {

    /**
     * Lists all Source entities.
     *
     * @Route("/", name="source")
     * @Security("is_granted('ROLE_GUESTY')")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('RestaurantBundle:Source')->findAll();

            return array(
                'entities' => $entities,
            );
    }

    /**
     * Creates a new Source entity.
     *
     * @Route("/", name="source_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_GUESTY')")
     * @Template("RestaurantBundle:Source:edit.html.twig")
     */
    public function createAction(Request $request) {
            $entity = new Source();
            $form = $this->createCreateForm($entity);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Success! The source has been created.');
                return $this->redirect($this->generateUrl('source_show', array('id' => $entity->getId())));
            }

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );
    }

    /**
     * Creates a form to create a Source entity.
     *
     * @param Source $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Source $entity) {
        $em = $this->getDoctrine()->getManager();
        $guesty = $em->getRepository('RestaurantBundle:SourceGuesty')->getArrayList();
        $form = $this->createForm(new SourceType($guesty), $entity, array(
            'action' => $this->generateUrl('source_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class'=>'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Source entity.
     *
     * @Route("/new/", name="source_new")
     * @Security("is_granted('ROLE_GUESTY')")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
            $entity = new Source();
            $form = $this->createCreateForm($entity);

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );
    }

    /**
     * Finds and displays a Source entity.
     *
     * @Route("/{id}/", name="source_show")
     * @Method("GET")
     * @Security("is_granted('ROLE_GUESTY')")
     * @Template()
     */
    public function showAction($id) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:Source')->find($id);

            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));
            }

            $deleteForm = $this->createEditForm($entity);

            return array(
                'entity' => $entity,
                'delete_form' => $deleteForm->createView(),
            );
    }

    /**
     * Displays a form to edit an existing Source entity.
     *
     * @Route("/{id}/edit/", name="source_edit")
     * @Security("is_granted('ROLE_GUESTY')")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:Source')->find($id);

            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
    }

    /**
     * Creates a form to edit a Source entity.
     *
     * @param Source $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Source $entity) {
        $em = $this->getDoctrine()->getManager();
        $guesty = $em->getRepository('RestaurantBundle:SourceGuesty')->getArrayList();
        $form = $this->createForm(new SourceType($guesty), $entity, array(
            'action' => $this->generateUrl('source_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update','attr' => array('class'=>'btn btn-primary')));

        return $form;
    }

    /**
     * Edits an existing Source entity.
     *
     * @Route("/{id}/", name="source_update")
     * @Method("PUT")
     * @Security("is_granted('ROLE_GUESTY')")
     * @Template("RestaurantBundle:Source:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:Source')->find($id);

            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createEditForm($entity);
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $em->flush();
                $this->addFlash('success', 'Success! The source has been changed.');
                return $this->redirect($this->generateUrl('source_show', array('id' => $id)));
            }

            return array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
    }

    /**
     * Deletes a Source entity.
     *
     * @Route("/{id}/delete/", name="source_delete")
     * @Security("is_granted('ROLE_GUESTY')")
     * @Method("GET")
     */
    public function deleteAction($id) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:Source')->find($id);
            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Success! The source has been removed.');

            return $this->redirect($this->generateUrl('source'));
    }

    /**
     * Creates a form to delete a Source entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('source_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}
