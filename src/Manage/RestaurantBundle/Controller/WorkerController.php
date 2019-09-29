<?php

namespace Manage\RestaurantBundle\Controller;
//ROLE_FORMS_SETTINGS
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\Worker;
use Manage\RestaurantBundle\Form\WorkerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * @Security("is_granted('ROLE_FORMS_SETTINGS')")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $limit = is_null($request->query->get('limit')) ? 30 : $request->query->get('limit');
        $offset = is_null($request->query->get('offset')) ? 0 : $request->query->get('offset');
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('RestaurantBundle:Worker')->findBy(array(),array("isactive"=>"DESC"), $limit, $offset );
            $finlista = $em->getRepository('RestaurantBundle:Worker')->findBy(array(),array(), 1, $offset + $limit  );
            return array(
                'entities' => $entities,
                'page' => $offset/$limit,
                'last' => count($finlista) == 0,
                'first' => $offset == 0
            );

    }
    /**
     * Creates a new Worker entity.
     *
     * @Route("/", name="worker_create")
     * @Method("POST")
     * @Template("RestaurantBundle:Worker:edit.html.twig")
     */
    public function createAction(Request $request)
    {
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
     * @Security("is_granted('ROLE_FORMS_SETTINGS')")
     * @Route("/new", name="worker_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
            $entity = new Worker();
            $form = $this->createCreateForm($entity);

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
            );
    }

    /**
     * Finds and displays a Worker entity.
     *
     * @Security("is_granted('ROLE_FORMS_SETTINGS')")
     * @Route("/{id}", name="worker_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:Worker')->find($id);

            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity' => $entity,
                'show_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
    }

    /**
     * Displays a form to edit an existing Worker entity.
     *
     * @Route("/{id}/edit", name="worker_edit")
     * @Security("is_granted('ROLE_FORMS_SETTINGS')")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:Worker')->find($id);

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
     * @Security("is_granted('ROLE_FORMS_SETTINGS')")
     * @Route("/{id}", name="worker_update")
     * @Method("PUT")
     * @Template("RestaurantBundle:Worker:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:Worker')->find($id);

            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

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
    /**
     * Deletes a Worker entity.
     *
     * @Route("/{id}/delete", name="worker_delete")
     */
    public function deleteAction(Request $request, $id)
    {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:Worker')->find($id);

            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Worker.'));
            }

            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Success! The worker has been removed.');
            return $this->redirect($this->generateUrl('worker'));

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
