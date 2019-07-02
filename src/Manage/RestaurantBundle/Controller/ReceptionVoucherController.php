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
 * @Route("/voucher")
 */
class ReceptionVoucherController extends Controller {

    /**
     * Lists all ReceptionVoucher entities.
     *
     * @Route("/", name="receptionvoucher")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('RestaurantBundle:ReceptionVoucher')->findAll();

            return array(
                'entities' => $entities,
            );
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a new ReceptionVoucher entity.
     *
     * @Route("/", name="receptionvoucher_create")
     * @Method("POST")
     * @Template("RestaurantBundle:ReceptionVoucher:edit.html.twig")
     */
    public function createAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new ReceptionVoucher();
            $form = $this->createCreateForm($entity);
            $form->handleRequest($request);
            if ($form->getData()->getIsactive() == null) {
                $form->getData()->setIsactive(false);
            }
            if ($form->getData()->getForreception() == null) {
                $form->getData()->setForreception(false);
            }

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                if ($form->getData()->getIsactive()) {
                    $em->getRepository('RestaurantBundle:ReceptionVoucher')->resetReceptionVoucher($form->getData()->getId());
                }
                $this->addFlash('success', 'Success! The voucher has been created.');
                return $this->redirect($this->generateUrl('receptionvoucher_show', array('id' => $entity->getId())));
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
     * Creates a form to create a ReceptionVoucher entity.
     *
     * @param ReceptionVoucher $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ReceptionVoucher $entity) {
        $form = $this->createForm(new ReceptionVoucherType(), $entity, array(
            'action' => $this->generateUrl('receptionvoucher_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Submit', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new ReceptionVoucher entity.
     *
     * @Route("/new/", name="receptionvoucher_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new ReceptionVoucher();
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
     * Finds and displays a ReceptionVoucher entity.
     *
     * @Route("/{id}/", name="receptionvoucher_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:ReceptionVoucher')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $deleteForm = $this->createEditForm($entity);
            $deleteForm->add('button', 'button', array('label' => 'Back'));
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
     * Displays a form to edit an existing ReceptionVoucher entity.
     *
     * @Route("/{id}/edit/", name="receptionvoucher_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:ReceptionVoucher')->find($id);

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
     * Creates a form to edit a ReceptionVoucher entity.
     *
     * @param ReceptionVoucher $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ReceptionVoucher $entity) {
        $form = $this->createForm(new ReceptionVoucherType(), $entity, array(
            'action' => $this->generateUrl('receptionvoucher_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Edits an existing ReceptionVoucher entity.
     *
     * @Route("/{id}/", name="receptionvoucher_update")
     * @Method("PUT")
     * @Template("RestaurantBundle:ReceptionVoucher:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:ReceptionVoucher')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createEditForm($entity);
            $editForm->handleRequest($request);

            if ($editForm->getData()->getIsactive() == null) {
                $editForm->getData()->setIsactive(false);
            }
            if ($editForm->getData()->getForreception() == null) {
                $editForm->getData()->setForreception(false);
            }

            if ($editForm->isValid()) {
                if ($editForm->getData()->getIsactive()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->getRepository('RestaurantBundle:ReceptionVoucher')->resetReceptionVoucher($editForm->getData()->getId());
                }

                $em->flush();
                $this->addFlash('success', 'Success! The voucher has been changed.');
                return $this->redirect($this->generateUrl('receptionvoucher_show', array('id' => $entity->getId())));
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
     * Deletes a ReceptionVoucher entity.
     *
     * @Route("/{id}/delete/", name="receptionvoucher_delete")
     * @Method("GET")
     */
    public function deleteAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:ReceptionVoucher')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Success! The voucher has been removed.');
            return $this->redirect($this->generateUrl('receptionvoucher'));
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a form to delete a ReceptionVoucher entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('receptionvoucher_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}
