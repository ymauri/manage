<?php

namespace Manage\AdminBundle\Controller;

use Manage\AdminBundle\Entity\RNotifierForm;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\AdminBundle\Entity\Notifier;
use Manage\AdminBundle\Form\NotifierType;
use Manage\AdminBundle\Form\RNotifierFormType;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Notifier controller.
 *
 * @Route("/notifier")
 */
class NotifierController extends Controller
{

    /**
     * Lists all  entities.
     *
     * @Route("/", name="notifier")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('AdminBundle:Notifier')->findAll();

            return array(
                'entities' => $entities,
            );
        }
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Lists all  entities.
     *
     * @Route("/mails/", name="notifier_mails")
     * @Method("GET")
     * @Template()
     */
    public function mailsAction(Request $request)
    {

        $limit = is_null($request->query->get('limit')) ? 30 : $request->query->get('limit');
        $offset = is_null($request->query->get('offset')) ? 0 : $request->query->get('offset');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();
            //$form = $em->getRepository('RestaurantBundle:Reception')->findOneBy(array('id'));
            $entities = $em->getRepository('AdminBundle:RNotifierForm')->findBy(array(),array('date'=>'DESC'), $limit, $offset );
            $finlista = $em->getRepository('AdminBundle:RNotifierForm')->findBy(array(),array('date'=>'DESC'), 1, $offset + $limit );
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
     * Lists all  entities.
     *
     * @Route("/mails/{id}/", name="notifier_mails_show")
     * @Method("GET")
     * @Template()
     */
    public function mailsShowAction($id)
    {
        
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AdminBundle:RNotifierForm')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $editForm = $this->createForm(new RNotifierFormType(), $entity);

            return $this->render('AdminBundle:Notifier:mails_show.html.twig', array(
                'entity' => $entity,
                'show_form' => $editForm->createView(),
            ));

        
    }

    /**
     * Creates a new Notifier entity.
     *
     * @Route("/", name="notifier_create")
     * @Method("POST")
     * @Template("AdminBundle:Notifier:edit.html.twig")
     */
    public function createAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new Notifier();
            $form = $this->createCreateForm($entity);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Success! The notifier has been created.');
                return $this->redirect($this->generateUrl('notifier_show', array('id' => $entity->getId())));
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
     * Creates a form to create a Notifier entity.
     *
     * @param Notifier $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Notifier $entity)
    {
        $form = $this->createForm(new NotifierType(), $entity, array(
            'action' => $this->generateUrl('notifier_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Notifier entity.
     *
     * @Route("/new/", name="notifier_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $entity = new Notifier();
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
     * Finds and displays a Notifier entity.
     *
     * @Route("/{id}/", name="notifier_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AdminBundle:Notifier')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $editForm = $this->createEditForm($entity);

            return array(
                'entity' => $entity,
                'show_form' => $editForm->createView(),
            );
        }
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Displays a form to edit an existing Notifier entity.
     *
     * @Route("/{id}/edit/", name="notifier_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AdminBundle:Notifier')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $editForm = $this->createEditForm($entity);

            return array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            );
        }
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
    * Creates a form to edit a Notifier entity.
    *
    * @param Notifier $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Notifier $entity)
    {
        $form = $this->createForm(new NotifierType(), $entity, array(
            'action' => $this->generateUrl('notifier_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    /**
     * Edits an existing Notifier entity.
     *
     * @Route("/{id}/", name="notifier_update")
     * @Method("PUT")
     * @Template("AdminBundle:Notifier:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN') {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AdminBundle:Notifier')->find($id);

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));
            }

            $editForm = $this->createEditForm($entity);
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $em->flush();
                $this->addFlash('success', 'Success! The notifier has been changed.');
                return $this->redirect($this->generateUrl('notifier_show', array('id' => $id)));
            }

            return array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            );
        }
        else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     *
     *
     * @Route("/body/{id}/", name="notifier_mail_body")
     * @Method("GET")
     */

    public function getBodyAction($id){
        $em = $this->getDoctrine()->getManager();

        $notifier = $em->getRepository('AdminBundle:RNotifierForm')->findOneBy(array('id'=>$id));

        return $this->render('AdminBundle:Notifier:template.html.twig', array(
            'body' => $notifier->getBody(),));
         
    }

    /**
     *
     *
     * @Route("/form/{id}/", name="notifier_mails_form")
     * @Method("GET")
     */

    public function mailsFormAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $limit = is_null($request->query->get('limit')) ? 30 : $request->query->get('limit');
        $offset = is_null($request->query->get('offset')) ? 0 : $request->query->get('offset');
        $entities = $em->getRepository('AdminBundle:RNotifierForm')->findBy(array('form'=>$id),array('date'=>'DESC'), $limit, $offset );
        $finlista = $em->getRepository('AdminBundle:RNotifierForm')->findBy(array('form'=>$id),array('date'=>'DESC'), 1, $offset + $limit );
        return $this->render('AdminBundle:Notifier:mails.html.twig', array(
            'entities' => $entities,
            'page' => $offset/$limit,
            'last' => count($finlista) == 0,
            'first' => $offset == 0
        ));
    }



}
