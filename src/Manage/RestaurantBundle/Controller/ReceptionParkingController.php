<?php

namespace Manage\RestaurantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\ReceptionParking;
use Manage\RestaurantBundle\Form\ReceptionParkingType;

/**
 * ReceptionParking controller.
 *
 * @Route("/receptionparking")
 */
class ReceptionParkingController extends Controller
{

    /**
     * Lists all ReceptionParking entities.
     *
     * @Route("/", name="receptionparking")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('RestaurantBundle:ReceptionParking')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new ReceptionParking entity.
     *
     * @Route("/", name="receptionparking_create")
     * @Method("POST")
     * @Template("RestaurantBundle:ReceptionParking:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ReceptionParking();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('receptionparking_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ReceptionParking entity.
     *
     * @param ReceptionParking $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ReceptionParking $entity)
    {
        $form = $this->createForm(new ReceptionParkingType(), $entity, array(
            'action' => $this->generateUrl('receptionparking_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ReceptionParking entity.
     *
     * @Route("/new", name="receptionparking_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ReceptionParking();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ReceptionParking entity.
     *
     * @Route("/{id}", name="receptionparking_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RestaurantBundle:ReceptionParking')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReceptionParking entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ReceptionParking entity.
     *
     * @Route("/{id}/edit", name="receptionparking_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RestaurantBundle:ReceptionParking')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReceptionParking entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a ReceptionParking entity.
    *
    * @param ReceptionParking $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ReceptionParking $entity)
    {
        $form = $this->createForm(new ReceptionParkingType(), $entity, array(
            'action' => $this->generateUrl('receptionparking_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ReceptionParking entity.
     *
     * @Route("/{id}", name="receptionparking_update")
     * @Method("PUT")
     * @Template("RestaurantBundle:ReceptionParking:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RestaurantBundle:ReceptionParking')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReceptionParking entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('receptionparking_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ReceptionParking entity.
     *
     * @Route("/{id}", name="receptionparking_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:ReceptionParking')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ReceptionParking entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('receptionparking'));
    }

    /**
     * Creates a form to delete a ReceptionParking entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('receptionparking_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
