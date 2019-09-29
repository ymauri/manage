<?php

namespace Manage\RestaurantBundle\Controller;

use Manage\RestaurantBundle\Entity\Folder;
use Manage\RestaurantBundle\Entity\RFolderFolder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\Furniture;
use Manage\RestaurantBundle\Form\FurnitureType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Manage\RestaurantBundle\Components\fpdf\FPDF;
use Manage\RestaurantBundle\Components\fpdi\Fpdi;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Furniture controller.
 *
 * @Route("/furniture")
 */
class FurnitureController extends Controller
{

    /**
     * Lists all Furniture entities.
     *
     * @Route("/", name="furniture")
     * @Security("is_granted('ROLE_SERVICE')")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $tree = $this->getWholeTree();
        /*$em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('RestaurantBundle:Furniture')->findAll();
        foreach ($entities as $key => $entity){
            $ruta = $this->generatePathFolder($entity->getFolder()->getId());
            $entity->setPathfolder($ruta);
            $em->flush();
            echo $key.'<br/>';
        }
        die;*/
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('RestaurantBundle:Folder')->findAll();

            return $this->render("RestaurantBundle:Furniture:treeview.html.twig", array(
                'entities' => $entities,
                'tree' => json_encode($tree)

            ));

    }

    /**
     * Creates a new Furniture entity.
     *
     * @Route("/", name="furniture_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_SERVICE')")
     * @Template("RestaurantBundle:Furniture:new.html.twig")
     */
    public function createAction(Request $request)
    {
            $entity = new Furniture();
            $form = $this->createCreateForm($entity);
            $originalpath = $form->getData()->getPathimage();
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $entity->uploadImage($this->container->getParameter('images.furniture'));
                $entity->setPathfolder($this->generatePathFolder($entity->getFolder()->getId()));
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Success! The furniture has been created.');
                return $this->redirect($this->generateUrl('furniture'));
            }
            $this->addFlash('error', 'Error! There was an error. Try again!');
            return $this->redirect($this->generateUrl('furniture'));
    }

    private function generatePathFolder($folder)
    {
        $result = "/" . $folder;
        $em = $this->getDoctrine()->getManager();
        $object = $em->getRepository("RestaurantBundle:RFolderFolder")->findOneBy(array('child' => $folder));
        if (!is_null($object) && $object->getFather()->getIsroot()) {
            $result = $result . '/' . $object->getFather()->getId() . '/';
            return $result;
        } else if (!is_null($object)) {
            return $result . $this->generatePathFolder($object->getFather()->getId());
        }
        return '';
    }

    private function getChildTree($parent)
    {
        $result = array();
        $em = $this->getDoctrine()->getManager();
        $objects = $em->getRepository("RestaurantBundle:Folder")->getChildrensNodes($parent);
        foreach ($objects as $key => $object) {
            $result[$key]['text'] = $object->getDetails();
            $result[$key]['id'] = $object->getId();
            $result[$key]['children'] = $this->getChildTree($object->getId());
        }
        return $result;
    }


    private function getWholeTree()
    {
        $result = array();
        $em = $this->getDoctrine()->getManager();
        $objects = $em->getRepository("RestaurantBundle:Folder")->findBy(array('isroot' => true), array('details' => 'ASC'));
        foreach ($objects as $key => $object) {
            $result[$key]['text'] = $object->getDetails();
            $result[$key]['id'] = $object->getId();
            $result[$key]['children'] = $this->getChildTree($object->getId());
            $result[$key]['state'] = array("opened" => true);
        }
        return $result;
    }


    /**
     * Creates a form to create a Furniture entity.
     *
     * @param Furniture $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Furniture $entity)
    {
        $form = $this->createForm(new FurnitureType(), $entity, array(
            'action' => $this->generateUrl('furniture_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Furniture entity.
     *
     * @Route("/new/{parent}", name="furniture_new")
     * @Method("GET")
     * @Security("is_granted('ROLE_SERVICE')")
     * @Template()
     */
    public function newAction($parent)
    {
            $entity = new Furniture();
            $form = $this->createCreateForm($entity);

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
                'parent' => $parent,
            );
    }

    /**
     * Finds and displays a Furniture entity.
     *
     * @Route("/{id}/", name="furniture_show")
     * @Method("GET")
     * @Security("is_granted('ROLE_SERVICE')")
     * @Template()
     */
    public function showAction($id)
    {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:Furniture')->find($id);

            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $form = $this->createEditForm($entity);

            return array(
                'entity' => $entity,
                'form' => $form->createView(),
                'image' => file_exists($this->container->getParameter('images.furniture') . '/' . $entity->getPathimage()) ? $entity->getPathimage() : false,

            );
    }

    /**
     * Displays a form to edit an existing Furniture entity.
     *
     * @Route("/{id}/edit/", name="furniture_edit")
     * @Security("is_granted('ROLE_SERVICE')")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:Furniture')->find($id);

            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity' => $entity,
                'form' => $editForm->createView(),
                'image' => file_exists($this->container->getParameter('images.furniture') . '/' . $entity->getPathimage()) ? $entity->getPathimage() : false,
                'delete_form' => $deleteForm->createView(),
            );
    }

    /**
     * Creates a form to edit a Furniture entity.
     *
     * @param Furniture $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Furniture $entity)
    {
        $form = $this->createForm(new FurnitureType(), $entity, array(
            'action' => $this->generateUrl('furniture_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Edits an existing Furniture entity.
     *
     * @Route("/{id}/", name="furniture_update")
     * @Method("PUT")
     * @Security("is_granted('ROLE_SERVICE')")
     * @Template("RestaurantBundle:Furniture:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('RestaurantBundle:Furniture')->find($id);

            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));

            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createEditForm($entity);
            $originalpath = $editForm->getData()->getPathimage();
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                if (null == $entity->getImage()) {
                    $entity->setPathimage($originalpath);
                } else {
                    $entity->uploadImage($this->container->getParameter('images.furniture'));
                    if ($originalpath != '') unlink($this->container->getParameter('images.furniture') . '/' . $originalpath);
                }
                $entity->setPathfolder($this->generatePathFolder($entity->getFolder()->getId()));
                $em->flush();
                $this->addFlash('success', 'Success! The furniture has been changed.');
                return $this->redirect($this->generateUrl('furniture'));
            }

            return array(
                'entity' => $entity,
                'form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
    }

    /**
     * Deletes a Furniture entity.
     *
     * @Route("/{id}/delete/", name="furniture_delete")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:Furniture')->find($id);
            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this page.'));
            }
            if (file_exists($this->container->getParameter('images.furniture') . '/' . $entity->getPathimage())) unlink($this->container->getParameter('images.furniture') . '/' . $entity->getPathimage());
            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Success! The furniture has been removed.');

            return $this->redirect($this->generateUrl('furniture'));
    }

    /**
     * Creates a form to delete a Furniture entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('furniture_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * @Route("/furniture/treeload", name="furniture_treeload")
     * @Security("is_granted('ROLE_SERVICE')")
     * @Method("GET")
     *      */
    public function treeloadAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $parent = $request->get('parent');
        $json = new JsonResponse();
        $data = array();
        if ($parent == "#") {
            $nodes = $em->getRepository("RestaurantBundle:Folder")->getRootNodes();
            //var_dump($nodes);die;
            foreach ($nodes as $key => $node) {
                $data[] = array(
                    "id" => $node->getId(),
                    "text" => $node->getDetails(),
                    "icon" => "fa fa-folder icon-lg icon-state-success",
                    "children" => !$node->getIssheet(),
                    "type" => "root"
                );
            }
        } else {
            $nodes = $em->getRepository("RestaurantBundle:Folder")->getChildrensNodes($parent);
            foreach ($nodes as $key => $node) {
                $data[] = array(
                    "id" => $node->getId(),
                    "text" => $node->getDetails(),
                    "icon" => "fa fa-folder icon-lg icon-state-success",
                    "children" => !$node->getIssheet(),
                );
            }
        }
        $json->setData(
            $data
        );
        return $json;
    }

    /**
     * @Route("/furniture/editnode", name="furniture_editnode")
     * @Security("is_granted('ROLE_SERVICE')")
     * @Method("GET")
     *      */
    public function editNodeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $name = $request->get('name');
        $parent = $request->get('parent');
        $old = $request->get('old');
        $json = new JsonResponse();
        if (is_numeric($id)) {
            $node = $em->getRepository("RestaurantBundle:Folder")->find($id);
            $node->setDetails($name);
            $em->persist($node);
            $em->flush();
            $json->setData(array(
                "action" => "update",
                "id" => $id
            ));

        } else {
            $node = new Folder();
            $node->setDetails($name);
            $node->setIsroot(!is_numeric($parent));
            $node->setIssheet(true);
            $em->persist($node);
            $em->flush();

            if (!is_numeric($parent)) {
                $parent = explode("_", $parent);
                $parent = $parent[1];
            }
            //if (is_numeric($parent)){
            $relation = new RFolderFolder();
            $folderparent = $em->getRepository("RestaurantBundle:Folder")->find($parent);
            $folderparent->setIssheet(false);
            $relation->setFather($folderparent);
            $relation->setChild($node);
            $em->persist($relation);
            //}
            $em->flush();
            $json->setData(array(
                "action" => "new",
                "id" => $node->getId()
            ));

        }
        return $json;
    }


    /**
     * @Route("/furniture/deletenode", name="furniture_deletenode")
     * @Security("is_granted('ROLE_SERVICE')")
     * @Method("GET")
     *
     */
    public function deleteNodeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $parent = $request->get('parent');
        $json = new JsonResponse();
        $childrens = $em->getRepository("RestaurantBundle:RFolderFolder")->findBy(array('father' => $id));
        $node = $em->getRepository("RestaurantBundle:Folder")->find($id);
        foreach ($childrens as $children) {
            $em->remove($children->getChild());
        }
        $em->remove($node);
        $em->flush();
        $json->setData(true);
        return $json;
    }

    /**
     * @Route("/furniture/tablecontent", name="furniture_tablecontent")
     * @Security("is_granted('ROLE_SERVICE')")
     * @Method("GET")
     *
     */
    public function tableContentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $entities = $em->getRepository("RestaurantBundle:Folder")->getChildrensFurnitures($id);
        if (count($entities) > 0) {
            echo $this->renderView("RestaurantBundle:Furniture:datatable.html.twig", array('entities' => $entities));
            die;
        } else {
            echo "<i>Empty folder. Select another one.</i>";
            die;
        }


    }

    /**
     * @Route("/move/", name="furniture_move")
     * @Security("is_granted('ROLE_SERVICE')")
     * @Method("POST")
     *
     */
    public function moveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $childrens = $request->get('childrens');
        $parent_id = $request->get('parent');
        $parent = $em->getRepository("RestaurantBundle:Folder")->find($parent_id);
        if (!is_null($parent)) {
            foreach ($childrens as $child) {
                $furniture = $em->getRepository("RestaurantBundle:Furniture")->find($child);
                if (!is_null($furniture)) {
                    $furniture->setFolder($parent);
                    $path = $this->generatePathFolder($parent_id);
                    $furniture->setPathfolder($path);
                    $em->persist($furniture);
                }
            }
            $em->flush();
        }
        $json = new JsonResponse();
        $json->setData(true);
        $this->addFlash('success', 'Success! The furniture has been moved to ' . $parent->getDetails());

        return $json;
    }

    /**
     *
     * @Route("/furniture/setimages/", name="furniture_setimages")
     * @Security("is_granted('ROLE_SERVICE')")
     * @Method("GET")
     *
     */

    public function setImagesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $furnitures = $em->getRepository("RestaurantBundle:Furniture")->findAll();

        foreach ($furnitures as $furniture) {
            if (substr($furniture->getPathimage(), 0, 4) === 'http') {

                $contextoEnvoltura = array(
                    "ssl" => array(
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                    ),
                );
                $name = md5(uniqid()) . '.jpeg';
                copy
                (
                    $furniture->getPathimage()
                    , $this->container->getParameter('images.furniture') . "/" . $name
                    , stream_context_create($contextoEnvoltura)
                );
                $furniture->setPathimage($name);
                $em->persist($furniture);
                echo $furniture->getId() . "<br/>";
                $em->flush();
            }
        }
        die;
    }

    /**
     *
     * @Route("/pdf/{id_folder}", name="furniture_folder")
     * @Security("is_granted('ROLE_SERVICE')")
     * @Method("GET")
     *
     */

    public function pdfAction(Request $request, $id_folder)
    {
        $folder = $this->getDoctrine()->getRepository('RestaurantBundle:Folder')->find($id_folder);
        if (!is_null($folder)) {
            $furnitures = $this->getDoctrine()->getRepository("RestaurantBundle:Folder")->getChildrensFurnitures($id_folder);
            $data = $this->getDoctrine()->getRepository("RestaurantBundle:Folder")->getStatisticsChildrens($folder->getId());
            $header = utf8_decode($folder->getDetails()." | Qty: ".$data['quantity']." | ").chr(128)." ".number_format($data['total'], 2, ",", ".");
            $totalpage = count($furnitures) % 5 == 0 ? number_format(count($furnitures) / 5, 0) + 1 : number_format(count($furnitures) / 5, 0);

            $pdf = new Fpdi('P', 'mm', array(210, 297));
            $pdf->AddPage();
            $fileLocator = $this->container->get('file_locator');
            $file = $fileLocator->locate('@RestaurantBundle/Resources/public/pdf/Template.pdf');
            $pdf->setSourceFile($file);
            //Primera página de la carpeta
            $page1 = $pdf->importPage(2);
            $pdf->useTemplate($page1, 0, 0);
            $pdf->SetFont('Helvetica', 'I', 10);
            $pdf->SetTextColor(60, 58, 58);
            $pdf->Cell(185, 10, utf8_decode(date('F jS, Y')), 0, 0, 0);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetTextColor(60, 58, 58);
            $pdf->SetXY(15, 19);
            $pdf->Cell(185, 15, $header, 0, 1, 1);
            $pdf->SetFont('Helvetica', '', 10);
            //$pdf->Image("http://localhost:5800/img/noimage.jpg", 10, 10, 20, 20, "JPG");
            $page = 1;
            $pdf->SetXY(10, 265);
            $pdf->SetFont('Helvetica', 'I', 10);
            $pdf->Cell(185, 10, "Page " . $page . ' of ' . $totalpage, 0, 0, 0);

            $positionY = 35;
            for ($i = 0; $i < count($furnitures); $i ++) {
                $img = $furnitures[$i]->getPathimage();

                //if (is_null($img) || substr($img, 0, 4) == "http") {
                    //$img = '18d10451a7c68a7a06daf0d0389f41a7.jpeg';
                //}
                if (!is_null($img)){
                    $fileimg = $_SERVER['DOCUMENT_ROOT'].'/web/uploads/images/furniture/'.$img;
                    $fileimg = str_replace('private', 'public', $fileimg);
                    if (file_exists($fileimg)){
                        $pdf->Image($fileimg, 15, $positionY, 35, 30, "JPG");
                    }
                }

                $pdf->SetXY(55, $positionY);
                $pdf->SetFont('Helvetica', 'B', 12);
                $pdf->MultiCell(185, 5, utf8_decode($furnitures[$i]->getName()), 0, "L", 0);
                $pdf->SetXY(55, $positionY + 5);
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->MultiCell(185, 5, "Quantity: " . utf8_decode($furnitures[$i]->getQuantity()), 0, "L", 0);
                $pdf->SetXY(55, $positionY + 10);
                $pdf->MultiCell(85, 5, "Price: " . chr(128) . number_format($furnitures[$i]->getPrice(), 2, ",", "."), 0, "L", 0);
                $pdf->SetXY(55, $positionY + 15);
                $pdf->MultiCell(185, 5, "Total Value: " . chr(128) . number_format($furnitures[$i]->getPrice() * $furnitures[$i]->getQuantity(), 2, ",", "."), 0, "L", 0);
                $pdf->SetXY(55, $positionY + 20);
                $pdf->MultiCell(185, 5, "Tags: " . implode(', ', $furnitures[$i]->getTagsArray()), 0, "L", 0);
                if ($furnitures[$i]->getDetails() != ""){
                    $pdf->SetXY(55, $positionY + 25);
                    $pdf->MultiCell(185, 5, "Notes: " . $furnitures[$i]->getDetails(), 0, "L", 0);
                }

               $positionY += 40;
                if ($i != 0 && $i % 5 == 0) {
                    $page++;
                    $pdf->AddPage();
                    $pdf->setSourceFile($file);
                    //Primera página de la carpeta
                    $page1 = $pdf->importPage(2);
                    $pdf->useTemplate($page1, 0, 0);
                    $pdf->SetFont('Helvetica', 'I', 10);
                    $pdf->SetTextColor(60, 58, 58);
                    $pdf->Cell(185, 10, utf8_decode(date('F jS, Y')), 0, 0, 0);
                    $pdf->SetFont('Helvetica', 'B', 10);
                    $pdf->SetTextColor(60, 58, 58);
                    $pdf->SetXY(15, 19);
                    $pdf->Cell(185, 15, $header, 0, 1, 1);
                    $pdf->SetFont('Helvetica', '', 10);
                    $positionY = 35;
                    $pdf->SetXY(10, 265);
                    $pdf->SetFont('Helvetica', 'I', 10);
                    $pdf->Cell(185, 10, "Page " . $page . ' of ' . $totalpage, 0, 0, 0);
                }
            }
            return new Response($pdf->Output(), 200, array(
                'Content-Type' => 'application/pdf'));

        }

    }

    /**
     * @Route("/pdfjson/{id_folder}", name="furniture_folder_json")
     * @Security("is_granted('ROLE_SERVICE')")
     * @Method("GET")
     */
    public function pdfJsonAction(Request $request, $id_folder)
    {
        $response = new JsonResponse();
        $result = array();
        $folder = $this->getDoctrine()->getRepository('RestaurantBundle:Folder')->find($id_folder);
        if (!is_null($folder)) {
            $furnitures = $this->getDoctrine()->getRepository("RestaurantBundle:Folder")->getChildrensFurnituresFull($id_folder);
            $data = $this->getDoctrine()->getRepository("RestaurantBundle:Folder")->getStatisticsChildrens($folder->getId());
            $result['header'] = $folder->getDetails()." | Qty: ".$data['quantity']." | €".number_format($data['total'], 2, ",", ".");
            $result['pagetotal'] = count($furnitures) % 5 == 0 ? number_format(count($furnitures) / 5, 0)  : number_format(count($furnitures) / 5, 0) + 1;
            $result['date'] = date('F jS, Y');

            for ($i = 0; $i < count($furnitures); $i ++) {
                //$fileimg = $_SERVER['DOCUMENT_ROOT'].'/uploads/images/furniture/'.$furnitures[$i]->getPathimage();
                $fileimg = $_SERVER['DOCUMENT_ROOT'].'/web/uploads/images/furniture/'.$furnitures[$i]->getPathimage();
                $fileimg = str_replace('private', 'public', $fileimg);
                $imagen = file_exists($fileimg) ? $furnitures[$i]->getPathimage() : '';
                $result['elements'][] = array(
                    'name'      => $furnitures[$i]->getName(),
                    'quantity'  => $furnitures[$i]->getQuantity(),
                    'price'     => number_format($furnitures[$i]->getPrice(), 2, ",", "."),
                    'totalvalue'=> number_format($furnitures[$i]->getTotalvalue(), 2, ",", "."),
                    'tags'      => implode(', ', $furnitures[$i]->getTagsArray()),
                    'notes'     => is_null($furnitures[$i]->getDetails()) ? "" : $furnitures[$i]->getDetails(),
                    'peacture'     => is_null($imagen) ? '' : $imagen
                );
            }
        }
        return $response->setData($result);
    }
}

