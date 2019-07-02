<?php

namespace Manage\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\AdminBundle\Entity\Worker;
use Manage\AdminBundle\Form\WorkerType;

/**
 * Worker controller.
 *
 * @Route("/worker")
 */
class WorkerController extends Controller
{

    /**
     * Lists all Worker entities.
     *
     * @Route("/", name="worker")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $limit = is_null($request->query->get('limit')) ? 30 : $request->query->get('limit');
        $offset = is_null($request->query->get('offset')) ? 0 : $request->query->get('offset');
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('AdminBundle:Worker')->findBy(array(),array("isactive"=>"DESC"), $limit, $offset );
            $finlista = $em->getRepository('AdminBundle:Worker')->findBy(array(),array(), 1, $offset + $limit  );
            return array(
                'entities' => $entities,
                'page' => $offset/$limit,
                'last' => count($finlista) == 0,
                'first' => $offset == 0
            );
        }
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }
    /**
     * Creates a new Worker entity.
     *
     * @Route("/", name="worker_create")
     * @Method("POST")
     * @Template("AdminBundle:Worker:edit.html.twig")
     */
    public function createAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new Worker();
            $form = $this->createCreateForm($entity);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Success! The worker has been created.');
                return $this->redirect($this->generateUrl('worker_show', array('id' => $entity->getId())));
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
     * Creates a form to create a Worker entity.
     *
     * @param Worker $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Worker $entity)
    {
        $form = $this->createForm(new WorkerType(), $entity, array(
            'action' => $this->generateUrl('worker_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Worker entity.
     *
     * @Route("/new", name="worker_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new Worker();
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

    /**
     * Finds and displays a Worker entity.
     *
     * @Route("/{id}", name="worker_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AdminBundle:Worker')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity' => $entity,
                'show_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Displays a form to edit an existing Worker entity.
     *
     * @Route("/{id}/edit", name="worker_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AdminBundle:Worker')->find($id);

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
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
    * Creates a form to edit a Worker entity.
    *
    * @param Worker $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Worker $entity)
    {
        $form = $this->createForm(new WorkerType(), $entity, array(
            'action' => $this->generateUrl('worker_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    /**
     * Edits an existing Worker entity.
     *
     * @Route("/{id}", name="worker_update")
     * @Method("PUT")
     * @Template("AdminBundle:Worker:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AdminBundle:Worker')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createEditForm($entity);
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $em->flush();
                $this->addFlash('success', 'Success! The worker has been changed.');
                return $this->redirect($this->generateUrl('worker_show', array('id' => $id)));
            }

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
     * Deletes a Worker entity.
     *
     * @Route("/{id}/delete", name="worker_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminBundle:Worker')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Worker.'));
            }

            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Success! The worker has been removed.');
            return $this->redirect($this->generateUrl('worker'));
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }

    }

    /**
     * Creates a form to delete a Worker entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('worker_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm()
        ;
    }
}
