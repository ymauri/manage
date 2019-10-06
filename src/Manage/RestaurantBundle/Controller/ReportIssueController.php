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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * @Security("is_granted('ROLE_USER')")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reportIssues = $em->getRepository('RestaurantBundle:ReportIssue')->findAll();
        return $this->render('RestaurantBundle:ReportIssue:index.html.twig', array(
            'reportIssues' => $reportIssues,
        ));
    }

    /**
     * Creates a new ReportIssue entity.
     *
     * @Route("/new", name="reportissue_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $reportIssue = new ReportIssue();
        $form = $this->createForm('Manage\RestaurantBundle\Form\ReportIssueType', $reportIssue);
        $form->handleRequest($request);
        // var_dump($form->getData());die;


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $reportIssue->uploadImage($this->container->getParameter('images.reportissue'));

            $em->persist($reportIssue);
            $em->flush();
            if ($this->isGranted('ROLE_MAINTENANCE_EDIT')) {
                return $this->redirectToRoute('reportissue_show', array('id' => $reportIssue->getId()));
            }
            return $this->redirectToRoute('reportissue_index');
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
    }

    /**
     * Finds and displays a ReportIssue entity.
     *
     * @Route("/{id}", name="reportissue_show")
     * @Security("is_granted('ROLE_MAINTENANCE_EDIT')")
     * @Method("GET")
     */
    public function showAction(ReportIssue $reportIssue)
    {
        $deleteForm = $this->createDeleteForm($reportIssue);

        return $this->render('RestaurantBundle:ReportIssue:show.html.twig', array(
            'reportIssue' => $reportIssue,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ReportIssue entity.
     *
     * @Route("/{id}/edit", name="reportissue_edit")
     * @Security("is_granted('ROLE_MAINTENANCE_EDIT')")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ReportIssue $reportIssue)
    {
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
    }

    /**
     * Deletes a ReportIssue entity.
     *
     * @Route("/{id}", name="reportissue_delete")
     * @Security("is_granted('ROLE_MAINTENANCE_EDIT')")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ReportIssue $reportIssue)
    {

        $form = $this->createDeleteForm($reportIssue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reportIssue);
            $em->flush();
        }

        return $this->redirectToRoute('reportissue_index');
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
     * @Security("is_granted('ROLE_MAINTENANCE_EDIT')")
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
     * @Method({"GET", "POST"})
     * */

    public function getFurnituresAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository("RestaurantBundle:Folder")->getChildrensFurnituresFull($id);
        $furnitures = array();
        foreach ($result as $item) {
            $tags = $item->getTags();
            //foreach ($tags as $tag) {
            //  if ($tag->getId() == 23) {
            $furnitures[$item->getId()] = $item->getName();
            //    break;
            //}
            //}
        }
        $response = new JsonResponse();
        $response->setData($furnitures);
        return $response;
    }


    /**
     * @Route("/getSubfolder/", name="reportissue_getsubfolder")
     * @Method({"GET", "POST"})
     * */

    public function getSubfolderAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository("RestaurantBundle:Folder")->getChildrensNodes($id);
        $nodes = array();
        foreach ($result as $item) {
            $nodes[$item->getId()] = $item->getDetails();
        }
        $response = new JsonResponse();
        $response->setData($nodes);
        return $response;
    }

    /**
     * @Route("/pdfjson/", name="reportissue_pdfjson")
     * @Security("is_granted('ROLE_CLEANING_LIST')")
     * @Method("GET")
     */
    public function pdfJsonAction(Request $request)
    {
        $response = new JsonResponse();
        $result = array();
        $id_furniture = $request->get('id_furniture');
        if (empty($id_furniture)) {
            $issues = $this->getDoctrine()->getRepository('RestaurantBundle:ReportIssue')->findBy(array('status' => array('Open', 'Wachten')), array('dated' => 'DESC', 'reportedat' => 'DESC'));
            $result['header'] = count($issues)." Issues";
        } else {
            $furniture = $this->getDoctrine()->getRepository('RestaurantBundle:Furniture')->find($id_furniture);
            $issues = $this->getDoctrine()->getRepository('RestaurantBundle:ReportIssue')->findBy(array('furniture' => $id_furniture), array('dated' => 'DESC', 'reportedat' => 'DESC'));
            $result['header'] = $furniture->getName();
        }
        $result['pagetotal'] = count($issues) % 6 == 0 ? number_format(count($issues) / 6, 0) : number_format(count($issues) / 6, 0) + 1;
        $result['date'] = date('F jS, Y');

        for ($i = 0; $i < count($issues); $i++) {
//            $fileimg = $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/reportissue/' . $issues[$i]->getPathimage();
            $fileimg = $_SERVER['DOCUMENT_ROOT'] . '/web/uploads/images/reportissue/' . $issues[$i]->getPathimage();
            $fileimg = str_replace('private', 'public', $fileimg);
            $imagen = file_exists($fileimg) ? $issues[$i]->getPathimage() : '';
            $result['elements'][] = array(
                'name' => $issues[$i]->getDetails(),
                'status' => $issues[$i]->getStatus(),
                'location' => !is_null($issues[$i]->getLocation()) ? $issues[$i]->getLocation()->getDetails() : '',
                'furniture' => !is_null($issues[$i]->getFurniture()) && !is_null($issues[$i]->getFurniture()->getName()) ? $issues[$i]->getFurniture()->getName() : '',
                'reportedby' => !empty($issues[$i]->getReporter()) ? $issues[$i]->getReporter()->getName() : '',
                'date' => $issues[$i]->getDated()->format('d-m-Y') . " " . $issues[$i]->getReportedat()->format('H:i:s'),
                'peacture' => is_null($imagen) ? '' : $imagen
            );
        }
        return $response->setData($result);
    }

}
