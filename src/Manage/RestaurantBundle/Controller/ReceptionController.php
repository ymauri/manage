<?php

namespace Manage\RestaurantBundle\Controller;

use Manage\RestaurantBundle\Entity\RNotifierForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\Reception;
use Manage\RestaurantBundle\Entity\BeginBill;
use Manage\RestaurantBundle\Entity\Bill;
use Manage\RestaurantBundle\Entity\Card;
use Manage\RestaurantBundle\Form\ReceptionType;
use Manage\RestaurantBundle\Form\BeginBillType;
use Manage\RestaurantBundle\Form\BillType;
use Manage\RestaurantBundle\Form\CardType;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Reception controller.
 *
 * @Route("/reception")
 */
class ReceptionController extends Controller {

    /**
     *
     * @Route("/new/", name="reception_new")
     * @Template()
     */
    public function newAction() {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()=='ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole()=='ROLE_RECEPTION'){

        $entity_basic = new Reception();
        $entity_basic->setGiftvouchers(json_encode($this->getVouchers()));
        $entity_basic->setParking(json_encode($this->getParking()));
        $entity_basic->setVoucher($this->getActiveVoucher());
        $entity_basic->setDated(new \DateTime());
        $entity_basic->setUpdated(new \DateTime());

        $entity_begin = new BeginBill();
        $entity_begin->setExtra(0);
        $entity_begin->setStandard(1);
        $entity_begin->setTotal(210.00);
        $entity_begin->setS20(5);
        $entity_begin->setS10(5);
        $entity_begin->setS5(5);
        $entity_begin->setS2(10);
        $entity_begin->setS1(10);
        $entity_begin->setS050(10);
        $entity_begin->setE20(5);
        $entity_begin->setE10(5);
        $entity_begin->setE5(5);
        $entity_begin->setE2(10);
        $entity_begin->setE1(10);
        $entity_begin->setE050(10);
        $entity_basic->setBeginbill($entity_begin);

        $entity_bill = new Bill();
        $entity_basic->setBill($entity_bill);

        $entity_card = new Card();
        $entity_card->setIscc(0);
        $entity_basic->setCard($entity_card);

        $em = $this->getDoctrine()->getManager();
        $em->persist($entity_basic);
        $em->flush();

        return $this->redirect($this->generateUrl('reception_edit', array('id' => $entity_basic->getId())));
        }
        else{
            return $this->render('RestaurantBundle:Exception:error403.html.twig');

        }
    }

    private function getVouchers() {
        $em = $this->getDoctrine()->getManager();
        $listado = $em->getRepository('RestaurantBundle:ReceptionVoucher')->findBy(array(
            'forreception' => true));
        $result = array();
        foreach ($listado as $value) {
            $result[] = array(
                'id' => $value->getId(),
                'details' => $value->getDetails() . ' ' . $value->getValue() . '€',
                'value' => $value->getValue(),
            );
        }
        return $result;
    }

    private function getParking() {
        $em = $this->getDoctrine()->getManager();
        $listado = $em->getRepository('RestaurantBundle:ReceptionParking')->findBy(array());
        $result = array();
        foreach ($listado as $value) {
            $result[] = array(
                'id' => $value->getId(),
                'details' => $value->getDetails() . ' ' . $value->getValue() . '€',
                'value' => $value->getValue(),
            );
        }
        return $result;
    }

    private function getActiveVoucher() {
        $em = $this->getDoctrine()->getManager();
        $value = $em->getRepository('RestaurantBundle:ReceptionVoucher')->findOneBy(array('isactive' => true));
        return $value->getValue();
    }

    /**
     * Lists all Reception entities.
     *
     * @Route("/date/{date}/", name="reception")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($date) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()=='ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole()=='ROLE_RECEPTION'){

        $partes = explode('-', $date);
        $date = $partes[1].'-'.$partes[0];

        $em = $this->getDoctrine()->getManager();
        //$entities = $em->getRepository('RestaurantBundle:Reception')->findBy(array('dated'=>'> 2018-01-01', 'dated'=>'< 2018-01-31'), array('dated'=>'DESC'));
        $consulta = $em->createQuery('SELECT r FROM RestaurantBundle:Reception r WHERE r.dated >= \''.$date.'-01\' AND r.dated <= \''.$date.'-31\' ORDER BY r.dated DESC');
        $entities = $consulta->getResult();

        $consulta = $em->createQuery('SELECT r.form, count(r.id) AS cantidad FROM RestaurantBundle:RNotifierForm r JOIN r.notifier n WHERE n.form LIKE \'Reception\' GROUP BY r.form');
        $notifier = $consulta->getResult();
        //var_dump($notifier);die;
        $result = array();
        foreach ($entities as $entity){
            foreach ($notifier as $not){
                if ($not['form'] == $entity->getId()){
                    $result[] = $not;
                }
            }
        }

        return array(
            'entities' => $entities,
            'notifier' => $result,
        );
        }
        return $this->render('RestaurantBundle:Exception:error403.html.twig');

    }


    /**
     * Finds and displays a Reception entity.
     *
     * @Route("/{id}/", name="reception_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()=='ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole()=='ROLE_RECEPTION') {

            $em = $this->getDoctrine()->getManager();

            $entity_basic = $em->getRepository('RestaurantBundle:Reception')->find($id);
            if (!$entity_basic) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
            }
            $form_basic = $this->createForm(new ReceptionType(), $entity_basic);
            $form_bill = $this->createForm(new BillType(), $entity_basic->getBill());
            $form_begin = $this->createForm(new BeginBillType(), $entity_basic->getBeginbill());
            $form_card = $this->createForm(new CardType(), $entity_basic->getCard());

            return $this->render('RestaurantBundle:Reception:edit.html.twig', array(
                'entity_basic' => $entity_basic,
                'form_basic' => $form_basic->createView(),
                'form_begin' => $form_begin->createView(),
                'form_bill' => $form_bill->createView(),
                'form_card' => $form_card->createView(),
                'show' => TRUE,
            ));
        }
        return $this->render('RestaurantBundle:Exception:error403.html.twig');

    }

    /**
     * Finds and displays a Reception entity.
     *
     * @Route("/{id}/delete/", name="reception_delete")
     * @Template()
     */
    public function deleteAction($id) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('RestaurantBundle:Reception')->find($id);
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()=='ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole()=='ROLE_RECEPTION') {

            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
            }
            $now = new \DateTime('now');
            //echo  $entity_basic->getUpdated()->diff($now)->d ; die;
            //if (strtotime($olddate->format('d-m-Y')) > strtotime($entity_basic->getUpdated()->format('d-m-Y')) && $user->getRole() != 'ROLE_SUPERADMIN') {
            if (($entity->getUpdated()->diff($now)->d >= 2) && ($user->getRole() != 'ROLE_SUPERADMIN')) {
                $this->addFlash('error', 'Error! This form can not be removed.');
                return $this->redirect($this->generateUrl('reception', array('date' => date('m-Y'))));
            }
            try {
                $em->remove($entity->getBill());
                $em->remove($entity->getBeginbill());
                $em->remove($entity->getCard());
                $em->remove($entity);
                $em->flush();
            } catch (\Exception $ex) {
                return $this->render('RestaurantBundle:Exception:exception.html.twig', array('message' => $ex));
            }
            $this->addFlash('success', 'Success! The form has been removed.');
            return $this->redirect($this->generateUrl('reception', array('date' => date('m-Y'))));
        }
        return $this->render('RestaurantBundle:Exception:error403.html.twig');

    }

    /**
     * Displays a form to edit an existing ReceptionParking entity.
     *
     * @Route("/{id}/edit/", name="reception_edit")
     */
    public function editAction($id) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()=='ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole()=='ROLE_RECEPTION') {

            $request = $this->getRequest();
            $em = $this->getDoctrine()->getManager();
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $entity_basic = $em->getRepository('RestaurantBundle:Reception')->find($id);
            if (!$entity_basic) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
            }
            //{% if 'today - 3 day' | date('d-m-Y') < entity.updated | date('d-m-Y') %}
            $olddate = new \DateTime('today - 1 day');
            $now = new \DateTime('now');
            //if (strtotime($olddate->format('d-m-Y')) > strtotime($entity_basic->getUpdated()->format('d-m-Y')) && $user->getRole() != 'ROLE_SUPERADMIN') {
            //if (($olddate->format('d-m-Y') == $entity_basic->getDated()->format('d-m-Y') && ($now->format('G') >= 8) || $entity_basic->getDated()->format('d-m-Y') != $now->format('d-m-Y') ) && $user->getRole() != 'ROLE_SUPERADMIN') {
            if (($entity_basic->getUpdated()->diff($now)->d >= 2) && ($user->getRole() != 'ROLE_SUPERADMIN')) {
                $this->addFlash('error', 'Error! This form can not be modified.');
                return $this->redirect($this->generateUrl('reception', array('date' => date('m-Y'))));
            }

            $form_basic = $this->createForm(new ReceptionType(), $entity_basic);
            $form_bill = $this->createForm(new BillType(), $entity_basic->getBill());
            $form_begin = $this->createForm(new BeginBillType(), $entity_basic->getBeginbill());
            $form_card = $this->createForm(new CardType(), $entity_basic->getCard());
            $response = new JsonResponse();
            if ($request->getMethod() == 'POST') {
                $form_basic->handleRequest($request);
                $form_begin->handleRequest($request);
                $form_bill->handleRequest($request);
                $form_card->handleRequest($request);
                try {
                    //if ($form_basic->isValid() && $form_begin->isValid() && $form_bill->isValid() && $form_card->isValid()) {
                    $entity_basic->setUpdated(new \DateTime());
                    $entity_basic->setBeginbill($this->validateFormBegin($entity_basic->getBeginbill()));
                    $entity_basic->setBill($this->validateFormBill($entity_basic->getBill()));
                    $entity_basic->setCard($this->validateFormCard($entity_basic->getCard()));
                    $not = $em->getRepository('RestaurantBundle:RNotifierForm')->findOneBy(array('form' => $entity_basic->getId()));
                    if (!is_null($entity_basic->getFinished())) {
                        $entity_basic->setFinished(new \DateTime());
                        $this->sendMail($id);
                    }
                    $em->persist($entity_basic);
                    $em->flush();

                    $response->setData('true');
                    return $response;
                    //}

                } catch (\Exception $ex) {
                    $response->setData($ex->getMessage());
                    return $response;
                }
                return $response;
            } else {
                return $this->render('RestaurantBundle:Reception:edit.html.twig', array(
                    'entity_basic' => $entity_basic,
                    'form_basic' => $form_basic->createView(),
                    'form_begin' => $form_begin->createView(),
                    'form_bill' => $form_bill->createView(),
                    'form_card' => $form_card->createView(),
                    'show' => FALSE,
                ));
            }
        }
        return $this->render('RestaurantBundle:Exception:error403.html.twig');

    }

    private function validateFormCard($entity_card) {
        return $entity_card;
    }

    private function validateFormBill($entity_bill) {
        $bills = $entity_bill->getE500() * 500 + $entity_bill->getE200() * 200 + $entity_bill->getE100() * 100 + $entity_bill->getE50() * 50 + $entity_bill->getE20() * 20 + $entity_bill->getE10() * 10 + $entity_bill->getE5() * 5;
        $coins = $entity_bill->getE2() * 2 + $entity_bill->getE1() + $entity_bill->getE050() * 0.50 + $entity_bill->getE020() * 0.20 + $entity_bill->getE010() * 0.10 + $entity_bill->getE005() * 0.05;
        $entity_bill->setWaarvan($coins);
        $entity_bill->setEind($bills + $coins);
        return $entity_bill;
    }

    private function validateFormBegin($entity_begin) {
        return $entity_begin;
    }

    private function sendMail($id){
        $em = $this->getDoctrine()->getManager();
        //Crear el notificador para este formulario.
        $notifier = $em->getRepository('RestaurantBundle:Notifier')->findOneBy(array('form'=>'Reception'));
        $entity_basic = $em->getRepository('RestaurantBundle:Reception')->findOneBy(array('id'=>$id));
        $mails_array = explode(';',$notifier->getMails());
        $mail_customer = \Swift_Message::newInstance()
            ->setFrom('info@log.towerleisure.nl')
            ->setTo($mails_array)
            ->setSubject("Receptie Kassa Cash & Log")
            ->setBody($this->renderView('RestaurantBundle:Reception:mail.html.twig', array(
                'entity_basic' => $entity_basic,
                'kasbon' => (array) json_decode($entity_basic->getVoorgeschoten()),
                'vouchers' => (array) json_decode($entity_basic->getGiftvouchersvalues()),
                'parking' => (array) json_decode($entity_basic->getParkingvalues()))))
            ->setContentType("text/html");

        $this->get('mailer')->send($mail_customer);
        $mailer = new RNotifierForm();
        $mailer->setNotifier($notifier);
        $mailer->setForm($id);
        $mailer->setBody((string)$mail_customer->getBody());
        $mailer->setDate(new \DateTime('now'));
        $mailer->setSubject("Receptie Kassa Cash & Log");
        $mailer->setTo($notifier->getMails());
        //Obtener el status del servidor de correo.
        $mailer->setStatus('Enviado');
        $em->persist($mailer);
        if (!is_null($notifier->getExternals())){
            $mails_array = explode(';',$notifier->getExternals());
            $mail_customer = \Swift_Message::newInstance()
                ->setFrom('info@log.towerleisure.nl')
                ->setTo($mails_array)
                ->setSubject("Receptie Kassa Cash & Log")
                ->setBody($this->renderView('RestaurantBundle:Reception:mail.html.twig', array(
                    'entity_basic' => $entity_basic,
                    'kasbon' => (array) json_decode($entity_basic->getVoorgeschoten()),
                    'vouchers' => (array) json_decode($entity_basic->getGiftvouchersvalues()),
                    'parking' => (array) json_decode($entity_basic->getParkingvalues()))))
                ->setContentType("text/html");

            $this->get('mailer')->send($mail_customer);
            $mailer = new RNotifierForm();
            $mailer->setNotifier($notifier);
            $mailer->setForm($id);
            $mailer->setBody((string)$mail_customer->getBody());
            $mailer->setDate(new \DateTime('now'));
            $mailer->setSubject("Receptie Kassa Cash & Log");
            $mailer->setTo($notifier->getExternals());
            //Obtener el status del servidor de correo.
            $mailer->setStatus('Enviado');
            $em->persist($mailer);
        }
        $em->flush();

    }

    /**
     * Displays a form to edit an existing ReceptionParking entity.
     *
     * @Route("/{id}/mail/", name="reception_mail")
     */
    public function mailAction($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_RECEPTION') {


            try {
                $this->sendMail($id);
            } catch (\Exception $ex) {
                return $this->render('RestaurantBundle:Exception:exception.html.twig', array('message' => $ex));
            }
            $em = $this->getDoctrine()->getManager();
            $entity_basic = $em->getRepository('RestaurantBundle:CashClosure')->findOneBy(array('id' => $id));
            //$date = $entity_basic->getDated()->format('m-Y');
            $this->addFlash('success', 'Success! The form has been sent.');
            return $this->redirect($this->generateUrl('reception', array('date' => date('m-Y'))));
        }
        return $this->render('RestaurantBundle:Exception:error403.html.twig');

    }
}
