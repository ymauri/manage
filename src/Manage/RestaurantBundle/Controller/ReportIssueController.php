<?php

namespace Manage\RestaurantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Manage\RestaurantBundle\Entity\ReportIssue;
use Manage\RestaurantBundle\Form\ReportIssueType;
use Manage\RestaurantBundle\Entity\Folder;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * ReportIssue controller.
 *
 * @Route("/reportissue")
 */
class ReportIssueController extends Controller
{
    /**
     * Lists all ReportIssue entities.
     *
     * @Route("/", name="reportissue_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_RECEPTION' || $user->getRole() == 'ROLE_REPORTER') {
            $em = $this->getDoctrine()->getManager();
            $reportIssues = $em->getRepository('RestaurantBundle:ReportIssue')->getListActiveIssue();
            return $this->render('RestaurantBundle:ReportIssue:index.html.twig', array(
                'reportIssues' => $reportIssues,
            ));
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a new ReportIssue entity.
     *
     * @Route("/new", name="reportissue_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_RECEPTION' || $user->getRole() == 'ROLE_REPORTER') {

            $reportIssue = new ReportIssue();
            $form = $this->createForm('Manage\RestaurantBundle\Form\ReportIssueType', $reportIssue);
            $form->handleRequest($request);
            // var_dump($form->getData());die;


            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $reportIssue->uploadImage($this->container->getParameter('images.reportissue'));

                $em->persist($reportIssue);
                $em->flush();

                return $this->redirectToRoute('reportissue_show', array('id' => $reportIssue->getId()));
            }
            $em = $this->getDoctrine()->getManager();
            $places = $em->getRepository("RestaurantBundle:Folder")->findBy(array('issheet' => 0, 'isroot' => 0), array('details' => 'ASC'));
            $locations = array();
            foreach ($places as $place) {
                $locations[$place->getId()] = $em->getRepository("RestaurantBundle:Folder")->getChildrensNodes($place->getId());
            }
            return $this->render('RestaurantBundle:ReportIssue:new.html.twig', array(
                'reportIssue' => $reportIssue,
                'form' => $form->createView(),
                'places' => $places,
                'locations' => $locations,
            ));
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }

    }

    /**
     * Finds and displays a ReportIssue entity.
     *
     * @Route("/{id}", name="reportissue_show")
     * @Method("GET")
     */
    public function showAction(ReportIssue $reportIssue)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_RECEPTION' || $user->getRole() == 'ROLE_REPORTER') {

            $deleteForm = $this->createDeleteForm($reportIssue);

            return $this->render('RestaurantBundle:ReportIssue:show.html.twig', array(
                'reportIssue' => $reportIssue,
                'delete_form' => $deleteForm->createView(),
            ));
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }

    }

    /**
     * Displays a form to edit an existing ReportIssue entity.
     *
     * @Route("/{id}/edit", name="reportissue_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ReportIssue $reportIssue)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_RECEPTION' || $user->getRole() == 'ROLE_REPORTER') {

            $deleteForm = $this->createDeleteForm($reportIssue);
            $editForm = $this->createForm('Manage\RestaurantBundle\Form\ReportIssueType', $reportIssue);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $reportIssue->uploadImage($this->container->getParameter('images.reportissue'));
                $em->persist($reportIssue);
                $em->flush();

                return $this->redirectToRoute('reportissue_index');
            }

            $em = $this->getDoctrine()->getManager();
            $places = $em->getRepository("RestaurantBundle:Folder")->findBy(array('issheet' => 0, 'isroot' => 0), array('details' => 'ASC'));
            $locations = array();
            foreach ($places as $place) {
                $locations[$place->getId()] = $em->getRepository("RestaurantBundle:Folder")->getChildrensNodes($place->getId());
            }

            return $this->render('RestaurantBundle:ReportIssue:edit.html.twig', array(
                'reportIssue' => $reportIssue,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
                'places' => $places,
                'locations' => $locations,
            ));
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Deletes a ReportIssue entity.
     *
     * @Route("/{id}", name="reportissue_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ReportIssue $reportIssue)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_RECEPTION' || $user->getRole() == 'ROLE_REPORTER') {

            $form = $this->createDeleteForm($reportIssue);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($reportIssue);
                $em->flush();
            }

            return $this->redirectToRoute('reportissue_index');
        } else {
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
    }

    /**
     * Creates a form to delete a ReportIssue entity.
     *
     * @param ReportIssue $reportIssue The ReportIssue entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ReportIssue $reportIssue)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reportissue_delete', array('id' => $reportIssue->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @Route("/changestatus/{id}", name="reportissue_changestatus")
     * @Method("POST")
     * */

    public function changeStatusAction(Request $request, $id)
    {
        $status = $request->get('status');
        $em = $this->getDoctrine()->getManager();
        $issue = $em->getRepository("RestaurantBundle:ReportIssue")->find($id);
        if (!is_null($issue)) {
            $issue->setStatus($status);
            $em->persist($issue);
            $em->flush();
        }
        $this->addFlash('success', 'Success! The Issue has been updated.');
        return $this->redirectToRoute('reportissue_index');
    }

    /**
     * @Route("/getfurnitures/", name="reportissue_getfurnitures")
     * @Method("POST")
     * */

    public function getFurnituresAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository("RestaurantBundle:Folder")->getChildrensReportFurnitures($id);
        $furnitures = array();
        foreach ($result as $item) {
            $tags = $item->getTags();
            foreach ($tags as $tag) {
                if ($tag->getId() == 23) {
                    $furnitures[$item->getId()] = $item->getName();
                    break;
                }
            }
        }
        $response = new JsonResponse();
        $response->setData($furnitures);
        return $response;
    }
}
