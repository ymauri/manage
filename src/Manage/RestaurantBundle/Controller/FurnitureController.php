<?php

namespace Manage\RestaurantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\Furniture;
use Manage\RestaurantBundle\Form\FurnitureType;

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
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('RestaurantBundle:Furniture')->findAll();

            return array(
                'entities' => $entities,
            );
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
     * @Template("RestaurantBundle:Furniture:edit.html.twig")
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
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Success! The furniture has been created.');
                return $this->redirect($this->generateUrl('furniture_show', array('id' => $entity->getId())));
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
     * @Route("/new/", name="furniture_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new Furniture();
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
                'edit_form' => $editForm->createView(),
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
                $em->flush();
                $this->addFlash('success', 'Success! The furniture has been changed.');
                return $this->redirect($this->generateUrl('furniture_show', array('id' => $id)));
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
            if ($entity->getPathimage() != '') unlink($this->container->getParameter('images.furniture').'/'.$entity->getPathimage());
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

}
