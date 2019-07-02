<?php

namespace Manage\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\AdminBundle\Entity\User;
use Manage\AdminBundle\Form\UserType;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller {

    /**
     * Lists all User entities.
     *
     * @Route("/", name="user")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $entities = $em->getRepository('AdminBundle:User')->findAll();
            return array(
                'entities' => $entities,
            );
        }
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }

    }

    /**
     * Creates a new User entity.
     *
     * @Route("/", name="user_create")
     * @Method("POST")
     * @Template("AdminBundle:User:edit.html.twig")
     */
    public function createAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new User();
            $form = $this->createCreateForm($entity);
            $form->handleRequest($request);
            $form->getData()->setEnable(true);
            try {

                if ($form->isValid()) {
                    $encoder = $this->get('security.encoder_factory')->getEncoder($entity);
                    $pass = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
                    $entity->setPassword($pass);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($entity);
                    $em->flush();

                    $this->addFlash('success', 'Success! The user has been created.');
                    return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
                }
            } catch (\Exception $ex) {
                return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
            }

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );
        }
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new/", name="user_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new User();
            $form = $this->createCreateForm($entity);
            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );
        }
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    private function createCreateForm(User $entity) {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}/", name="user_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getId()== $id) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AdminBundle:User')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this User.'));
            }

            $deleteForm = $this->createEditForm($entity);
            $deleteForm->add('button', 'button', array('label' => 'Back'));
            return array(
                'entity' => $entity,
                'delete_form' => $deleteForm->createView(),
            );
        }
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit/", name="user_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminBundle:User')->find($id);

        if (!$entity) {
            return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this User.'));
        }

        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getId() == $id) {
            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(User $entity) {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}/update/", name="user_update")
     * @Method("PUT")
     * @Template("AdminBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:User')->find($id);

        if (!$entity) {
            return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this User.'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $passwordOriginal = $entity->getPassword();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        try {
            if ($editForm->isValid()) {
                if ($editForm->getData()->getPassword() != $passwordOriginal) {
                    $encoder = $this->get('security.encoder_factory')->getEncoder($entity);
                    $passwordCodificado = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
                    $entity->setPassword($passwordCodificado);
                }
                $em->flush();
                $this->addFlash('success', 'Success! The user has been changed.');
                return $this->redirect($this->generateUrl('user_show', array('id' => $id)));
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
     * Deletes a User entity.
     *
     * @Route("/{id}/delete/", name="user_delete")
     * @Method("GET")
     */
    public function deleteAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminBundle:User')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Success! The user has been removed.');
            return $this->redirect($this->generateUrl('user'));
        }
        else{
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('user_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-primary')))
                        ->getForm()
        ;
    }




}
