<?php

namespace Manage\RestaurantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\SourceGuesty;
use Manage\RestaurantBundle\Form\SourceGuestyType;

/**
 * SourceGuesty controller.
 *
 * @Route("/sourceguesty")
 */
class SourceGuestyController extends Controller {

    /**
     * Lists all SourceGuesty entities.
     *
     * @Route("/", name="sourceguesty")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('RestaurantBundle:SourceGuesty')->findAll();

            return array(
                'entities' => $entities,
            );
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a new SourceGuesty entity.
     *
     * @Route("/", name="sourceguesty_create")
     * @Method("POST")
     * @Template("RestaurantBundle:SourceGuesty:edit.html.twig")
     */
    public function createAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new SourceGuesty();
            $form = $this->createCreateForm($entity);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Success! The sourceguesty has been created.');
                return $this->redirect($this->generateUrl('sourceguesty_show', array('id' => $entity->getId())));
            }

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a form to create a SourceGuesty entity.
     *
     * @param SourceGuesty $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SourceGuesty $entity) {
        $form = $this->createForm(new SourceGuestyType(), $entity, array(
            'action' => $this->generateUrl('sourceguesty_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class'=>'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new SourceGuesty entity.
     *
     * @Route("/new/", name="sourceguesty_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new SourceGuesty();
            $form = $this->createCreateForm($entity);

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Finds and displays a SourceGuesty entity.
     *
     * @Route("/{id}/", name="sourceguesty_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:SourceGuesty')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));
            }

            $deleteForm = $this->createEditForm($entity);

            return array(
                'entity' => $entity,
                'delete_form' => $deleteForm->createView(),
            );
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Displays a form to edit an existing SourceGuesty entity.
     *
     * @Route("/{id}/edit/", name="sourceguesty_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:SourceGuesty')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a form to edit a SourceGuesty entity.
     *
     * @param SourceGuesty $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(SourceGuesty $entity) {
        $em = $this->getDoctrine()->getManager();
        $guesty = $em->getRepository('RestaurantBundle:SourceGuesty')->getArrayList();
        $form = $this->createForm(new SourceGuestyType($guesty), $entity, array(
            'action' => $this->generateUrl('sourceguesty_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update','attr' => array('class'=>'btn btn-primary')));

        return $form;
    }

    /**
     * Edits an existing SourceGuesty entity.
     *
     * @Route("/{id}/", name="sourceguesty_update")
     * @Method("PUT")
     * @Template("RestaurantBundle:SourceGuesty:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:SourceGuesty')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createEditForm($entity);
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $em->flush();
                $this->addFlash('success', 'Success! The sourceguesty has been changed.');
                return $this->redirect($this->generateUrl('sourceguesty_show', array('id' => $id)));
            }

            return array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Deletes a SourceGuesty entity.
     *
     * @Route("/{id}/delete/", name="sourceguesty_delete")
     * @Method("GET")
     */
    public function deleteAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:SourceGuesty')->find($id);
            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Success! The sourceguesty has been removed.');

            return $this->redirect($this->generateUrl('sourceguesty'));
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a form to delete a SourceGuesty entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('sourceguesty_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}
