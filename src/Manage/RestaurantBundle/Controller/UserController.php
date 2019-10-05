<?php

namespace Manage\RestaurantBundle\Controller;

use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Form\Type\ProfileFormType;
use FOS\UserBundle\Form\Type\UsernameFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\User;
use Manage\RestaurantBundle\Form\UserType;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\UserBundle\Model\UserManagerInterface;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @Route("/", name="user")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $um = $this->container->get('fos_user.user_manager');
        $users = $um->findUsers();
        return array(
            'entities' => $users,
        );


    }

    /**
     * Creates a new User entity.
     *
     * @Route("/", name="user_create")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Method("POST")
     * @Template("RestaurantBundle:User:edit.html.twig")
     */
    public function createAction(Request $request)
    {

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
            return $this->render('RestaurantBundle:Exception:exception.html.twig', array('message' => $ex));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new/", name="user_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new User();
            $form = $this->createCreateForm($entity);
            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );
        } else {
            return $this->render('RestaurantBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    private function createCreateForm(User $entity)
    {
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
    public function showAction($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getId() == $id) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:User')->find($id);

            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this User.'));
            }

            $deleteForm = $this->createEditForm($entity);
            $deleteForm->add('button', 'button', array('label' => 'Back'));
            return array(
                'entity' => $entity,
                'delete_form' => $deleteForm->createView(),
            );
        } else {
            return $this->render('RestaurantBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit/", name="user_edit")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $user = $this->getDoctrine()->getRepository('RestaurantBundle:User')->find($id);
        if (!$user) {
            return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this User.'));
        }

        $editForm = $this->createEditForm($user);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );

    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(User $entity)
    {
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
     * @Template("RestaurantBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RestaurantBundle:User')->find($id);

        if (!$entity) {
            return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this User.'));
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
                return $this->redirect($this->generateUrl('user', array('id' => $id)));
            }
        } catch (\Exception $ex) {
            return $this->render('RestaurantBundle:Exception:exception.html.twig', array('message' => $ex));
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
    public function deleteAction($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:User')->find($id);

            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Success! The user has been removed.');
            return $this->redirect($this->generateUrl('user'));
        } else {
            return $this->render('RestaurantBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm();
    }


}
