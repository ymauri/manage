<?php

namespace Manage\RestaurantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\ReceptionVoucher;
use Manage\RestaurantBundle\Form\ReceptionVoucherType;

/**
 * ReceptionVoucher controller.
 *
 * @Route("/receptionvoucher")
 */
class ReceptionVoucherController extends Controller
{

    /**
     * Lists all ReceptionVoucher entities.
     *
     * @Route("/", name="receptionvoucher")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('RestaurantBundle:ReceptionVoucher')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new ReceptionVoucher entity.
     *
     * @Route("/", name="receptionvoucher_create")
     * @Method("POST")
     * @Template("RestaurantBundle:ReceptionVoucher:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ReceptionVoucher();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('receptionvoucher_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ReceptionVoucher entity.
     *
     * @param ReceptionVoucher $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ReceptionVoucher $entity)
    {
        $form = $this->createForm(new ReceptionVoucherType(), $entity, array(
            'action' => $this->generateUrl('receptionvoucher_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ReceptionVoucher entity.
     *
     * @Route("/new", name="receptionvoucher_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ReceptionVoucher();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ReceptionVoucher entity.
     *
     * @Route("/{id}", name="receptionvoucher_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RestaurantBundle:ReceptionVoucher')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReceptionVoucher entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ReceptionVoucher entity.
     *
     * @Route("/{id}/edit", name="receptionvoucher_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RestaurantBundle:ReceptionVoucher')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReceptionVoucher entity.');
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
    * Creates a form to edit a ReceptionVoucher entity.
    *
    * @param ReceptionVoucher $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ReceptionVoucher $entity)
    {
        $form = $this->createForm(new ReceptionVoucherType(), $entity, array(
            'action' => $this->generateUrl('receptionvoucher_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ReceptionVoucher entity.
     *
     * @Route("/{id}", name="receptionvoucher_update")
     * @Method("PUT")
     * @Template("RestaurantBundle:ReceptionVoucher:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RestaurantBundle:ReceptionVoucher')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReceptionVoucher entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('receptionvoucher_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ReceptionVoucher entity.
     *
     * @Route("/{id}", name="receptionvoucher_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:ReceptionVoucher')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ReceptionVoucher entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('receptionvoucher'));
    }

    /**
     * Creates a form to delete a ReceptionVoucher entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('receptionvoucher_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
