<?php

namespace Manage\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\AdminBundle\Entity\Role;
use Manage\AdminBundle\Form\RoleType;

/**
 * Role controller.
 *
 * @Route("/role")
 */
class RoleController extends Controller {

    /**
     * Lists all Role entities.
     *
     * @Route("/", name="role")
     * @Method("POST")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminBundle:Role')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Role entity.
     *
     * @Route("/", name="role_create")
     * @Method("POST")
     * @Template("AdminBundle:Role:edit.html.twig")
     */
    public function createAction(Request $request) {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_SUPERADMIN')) {
            return $this->render('AdminBundle:Exception:error403.html.twig');
        }
        $entity = new Role();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        try {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('role_show', array('id' => $entity->getId())));
            }
        } catch (\Exception $ex) {
            return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Role entity.
     *
     * @param Role $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Role $entity) {
        $form = $this->createForm(new RoleType(), $entity, array(
            'action' => $this->generateUrl('role_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Role entity.
     *
     * @Route("/new/", name="role_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_SUPERADMIN')) {
            return $this->render('AdminBundle:Exception:error403.html.twig');
        }
        $entity = new Role();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Role entity.
     *
     * @Route("/{id}/", name="role_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Role')->find($id);

        if (!$entity) {
            return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Role.'));
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Role entity.
     *
     * @Route("/{id}/edit/", name="role_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Role')->find($id);

        if (!$entity) {
            return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Role.'));
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
     * Creates a form to edit a Role entity.
     *
     * @param Role $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Role $entity) {
        $form = $this->createForm(new RoleType(), $entity, array(
            'action' => $this->generateUrl('role_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Role entity.
     *
     * @Route("/{id}/", name="role_update")
     * @Method("PUT")
     * @Template("AdminBundle:Role:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Role')->find($id);

        if (!$entity) {
            return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Role.'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        try {
            if ($editForm->isValid()) {
                $em->flush();

                return $this->redirect($this->generateUrl('role_edit', array('id' => $id)));
            }
        } catch (\Exception $ex) {
            return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Role entity.
     *
     * @Route("/{id}/", name="role_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminBundle:Role')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message'=>'Unable to find this Role.'));
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('role'));
    }

    /**
     * Creates a form to delete a Role entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('role_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}
