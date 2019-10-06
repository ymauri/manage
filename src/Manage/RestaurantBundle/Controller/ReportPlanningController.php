<?php

namespace Manage\RestaurantBundle\Controller;

use Manage\RestaurantBundle\Entity\ReportIssue;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Manage\RestaurantBundle\Entity\ReportPlanning;
use Manage\RestaurantBundle\Form\ReportPlanningType;
use Manage\RestaurantBundle\Entity\Folder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * ReportPlanning controller.
 *
 * @Route("/reportplanning")
 */
class ReportPlanningController extends Controller
{
    /**
     * Lists all ReportPlanning entities.
     *
     * @Route("/", name="reportplanning_index")
     * @Security("is_granted('ROLE_MAINTENANCE_PLANNING')")
     * @Method("GET")
     */
    public function indexAction()
    {
     $em = $this->getDoctrine()->getManager();

            $reportPlannings = $em->getRepository('RestaurantBundle:ReportPlanning')->findAll();

            return $this->render('RestaurantBundle:ReportPlanning:index.html.twig', array(
                'reportPlannings' => $reportPlannings,
            ));
    }

    /**
     * Creates a new ReportPlanning entity.
     *
     * @Route("/new", name="reportplanning_new")
     * @Security("is_granted('ROLE_MAINTENANCE_PLANNING')")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
            $reportPlanning = new ReportPlanning();
            $form = $this->createForm('Manage\RestaurantBundle\Form\ReportPlanningType', $reportPlanning);
            $form->handleRequest($request);
            // var_dump($form->getData());die;


            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $reportPlanning->uploadImage($this->container->getParameter('images.reportplanning'));

                $em->persist($reportPlanning);
                $em->flush();

                return $this->redirectToRoute('reportplanning_show', array('id' => $reportPlanning->getId()));
            }
            $em = $this->getDoctrine()->getManager();
            $places = $em->getRepository("RestaurantBundle:Folder")->findBy(array('issheet' => 0, 'isroot' => 0), array('details' => 'ASC'));
            $folders = array();
            foreach ($places as $place) {
                $folders[$place->getId()] = $em->getRepository("RestaurantBundle:Folder")->getChildrensNodes($place->getId());
            }
            return $this->render('RestaurantBundle:ReportPlanning:new.html.twig', array(
                'reportPlanning' => $reportPlanning,
                'form' => $form->createView(),
                'places' => $places,
                'folders' => $folders,
            ));
    }

    /**
     * Finds and displays a ReportPlanning entity.
     *
     * @Route("/{id}", name="reportplanning_show")
     * @Security("is_granted('ROLE_MAINTENANCE_PLANNING')")
     * @Method("GET")
     */
    public function showAction(ReportPlanning $reportPlanning)
    {
            return $this->render('RestaurantBundle:ReportPlanning:show.html.twig', array(
                'reportPlanning' => $reportPlanning,
            ));
    }

    /**
     * Displays a form to edit an existing ReportPlanning entity.
     *
     * @Route("/{id}/edit", name="reportplanning_edit")
     * @Security("is_granted('ROLE_MAINTENANCE_PLANNING')")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ReportPlanning $reportPlanning)
    {

            $editForm = $this->createForm('Manage\RestaurantBundle\Form\ReportPlanningType', $reportPlanning);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $reportPlanning->uploadImage($this->container->getParameter('images.reportplanning'));
                $em->persist($reportPlanning);
                $em->flush();

                return $this->redirectToRoute('reportplanning_index');
            }

            $em = $this->getDoctrine()->getManager();
            $places = $em->getRepository("RestaurantBundle:Folder")->findBy(array('issheet' => 0, 'isroot' => 0), array('details' => 'ASC'));
            $locations = array();
            foreach ($places as $place) {
                $locations[$place->getId()] = $em->getRepository("RestaurantBundle:Folder")->getChildrensNodes($place->getId());
            }

            return $this->render('RestaurantBundle:ReportPlanning:edit.html.twig', array(
                'reportPlanning' => $reportPlanning,
                'form' => $editForm->createView(),
                'places' => $places,
                'folders' => $locations,
            ));
    }

    /**
     * Deletes a ReportPlanning entity.
     *
     * @Route("/{id}/delete", name="reportplanning_delete")
     * @Security("is_granted('ROLE_MAINTENANCE_PLANNING')")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
            $em = $this->getDoctrine()->getManager();
            $reportPlanning = $em->getRepository("RestaurantBundle:ReportPlanning")->find($id);
            if (!is_null($reportPlanning)) {
                $em->remove($reportPlanning);
                $em->flush();
                $this->addFlash('success', 'Success! The Report Planning has been removed.');
            } else $this->addFlash('error', "Error! Report Planning not found.");

            return $this->redirectToRoute('reportplanning_index');
    }

    /**
     * @Route("/changestatus/{id}", name="reportplanning_changestatus")
     * @Security("is_granted('ROLE_MAINTENANCE_PLANNING')")
     * @Method("POST")
     * */

    public function changeStatusAction(Request $request, $id)
    {

            $status = $request->get('status');
            $em = $this->getDoctrine()->getManager();
            $issue = $em->getRepository("RestaurantBundle:ReportPlanning")->find($id);
            if (!is_null($issue)) {
                $issue->setStatus($status);
                $em->persist($issue);
                $em->flush();
            }
            $this->addFlash('success', 'Success! The Report Planning has been updated.');
            return $this->redirectToRoute('reportplanning_index');
    }

    /**
     * @Route("/getfurnitures/", name="reportplanning_getfurnitures")
     * @Security("is_granted('ROLE_MAINTENANCE_PLANNING')")
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


    /**
     * @Route("/createissue/", name="reportplanning_createissue")
     * @Method("GET")
     *
     * */

    public function createIssueAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reportPlanning = $em->getRepository('RestaurantBundle:ReportPlanning')->findAll();

        foreach ($reportPlanning as $report) {
            if ($report->canApplyToday()) {
                $issue = new ReportIssue();
                $issue->setLocation($report->getFolder());
                $issue->setFurniture($report->getFurniture());
                $issue->setReporter(null);
                $issue->setDated(new \DateTime());
                $issue->setReportedat(new \DateTime());
                $issue->setDetails($report->getDetails());
                //Copiar la imagen de un directorio a otro
                //copy($this->container->getParameter('images.reportplanning') . DIRECTORY_SEPARATOR . $report->getPathimage(), $this->container->getParameter('images.reportissue') . DIRECTORY_SEPARATOR . $report->getPathimage());
                exec('copy "'.$this->container->getParameter('images.reportplanning').DIRECTORY_SEPARATOR. $report->getPathimage().'" "'.$this->container->getParameter('images.reportissue').DIRECTORY_SEPARATOR.$report->getPathimage().'"');
                $issue->setPathimage($report->getPathimage());
                $issue->setPriority($report->getPriority());
                $report->setUpdated(new \DateTime());
                $em->persist($issue);
                $em->persist($report);
                $em->flush();

            }
        }
        die;
    }

}
