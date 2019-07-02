<?php

namespace Manage\RestaurantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Manage\RestaurantBundle\Entity\CashClosure;
use Manage\RestaurantBundle\Entity\CashClosureTotal;
use Manage\RestaurantBundle\Entity\BeginBill;
use Manage\RestaurantBundle\Entity\Bill;
use Manage\RestaurantBundle\Entity\Card;
use Manage\RestaurantBundle\Form\CashClosureType;
use Manage\RestaurantBundle\Form\CashClosureTotalType;
use Manage\RestaurantBundle\Form\BeginBillType;
use Manage\RestaurantBundle\Form\BillType;
use Manage\RestaurantBundle\Form\CardType;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Manage\AdminBundle\Entity\RNotifierForm;
use Manage\AdminBundle\Entity\Parameters;

/**
 * CashClosure controller.
 *
 * @Route("/service")
 */
class CashClosureController extends Controller {

    /**
     * Displays a form to create a new CashClosure entity.
     *
     * @Route("/new/", name="cashclosure_new")
     * @Template()
     */
    public function newAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_SERVICE') {

            $entity_basic = new CashClosure();
            $entity_basic->setGiftvouchers(json_encode($this->getVouchers()));
            $entity_basic->setExtvouchers(json_encode($this->getVouchersExt()));
            $entity_basic->setDated(new \DateTime('today'));
            $entity_basic->setUpdated(new \DateTime('today'));

            $entity_total = new CashClosureTotal();
            $entity_basic->setTotal($entity_total);

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
            $entity_basic->setBeginbill($entity_begin);

            $entity_bill = new Bill();
            $entity_basic->setBill($entity_bill);

            $entity_card = new Card();
            $entity_card->setIscc(1);
            $entity_basic->setCard($entity_card);

            $em = $this->getDoctrine()->getManager();
            $iva = $em->getRepository('AdminBundle:Parameters')->getServiceIvaActive();
            $em->persist($entity_basic);
            $reception = $em->getRepository('RestaurantBundle:Reception')->findOneBy(array('dated' => $entity_basic->getDated()));
            if (!is_null($reception)) {
                $entity_basic->setVoucherday($reception->getVdamount());
                $entity_basic->setVouchernight($reception->getVnamount());
            }
            //var_dump($iva);die;
            $em->persist($entity_basic);
            $em->flush();

            return $this->redirect($this->generateUrl('cashclosure_edit', array('id' => $entity_basic->getId())));
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

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

    private function getVouchersExt() {
        $em = $this->getDoctrine()->getManager();
        $listado = $em->getRepository('RestaurantBundle:ReceptionVoucher')->findBy(array(
            'isext' => true), array(
            'details' => 'ASC'
        ));
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

    private function validateFormTotal($entity_total) {
        $xtotal = $entity_total->getXlaag() + $entity_total->getXkitchen() + $entity_total->getXhoog() + $entity_total->getXparking() + $entity_total->getXentry() + $entity_total->getXspacesrent() + $entity_total->getXothers();
        $entity_total->setXtotal($xtotal);

        $ztotal = $entity_total->getZlaag() + $entity_total->getZkitchen() + $entity_total->getZhoog() + $entity_total->getZparking() + $entity_total->getZentry() + $entity_total->getZspacesrent() + $entity_total->getZothers();
        $entity_total->setZtotal($ztotal);

        return $entity_total;
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

    /**
     * Lists all CashClosure entities.
     *
     * @Route("/date/{date}/", name="cashclosure")
     * @Template()
     */
    public function indexAction($date) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_SERVICE') {

            $partes = explode('-', $date);
            $date = $partes[1] . '-' . $partes[0];

            $em = $this->getDoctrine()->getManager();
            $consulta = $em->createQuery('SELECT r FROM RestaurantBundle:CashClosure r WHERE r.dated >= \'' . $date . '-01\' AND r.dated <= \'' . $date . '-31\' ORDER BY r.dated DESC');
            $entities = $consulta->getResult();

            $consulta = $em->createQuery('SELECT r.form, count(r.id) AS cantidad FROM AdminBundle:RNotifierForm r JOIN r.notifier n WHERE n.form LIKE \'Service\' GROUP BY r.form');
            $notifier = $consulta->getResult();
            //var_dump($notifier);die;
            $result = array();
            foreach ($entities as $entity) {
                foreach ($notifier as $not) {
                    if ($not['form'] == $entity->getId()) {
                        $result[] = $not;
                    }
                }
            }
            return $this->render('RestaurantBundle:CashClosure:index.html.twig', array(
                'entities' => $entities, 'notifier' => $result,
            ));
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }

    /**
     * Lists all CashClosure entities.
     *
     * @Route("/{id}/", name="cashclosure_show")
     * @Template()
     */
    public function showAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_SERVICE') {

            $em = $this->getDoctrine()->getManager();

            $entity_basic = $em->getRepository('RestaurantBundle:CashClosure')->find($id);
            if (!$entity_basic) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
            }
            $form_basic = $this->createForm(new CashClosureType(), $entity_basic);
            $form_total = $this->createForm(new CashClosureTotalType(), $entity_basic->getTotal());
            $form_bill = $this->createForm(new BillType(), $entity_basic->getBill());
            $form_begin = $this->createForm(new BeginBillType(), $entity_basic->getBeginbill());
            $form_card = $this->createForm(new CardType(), $entity_basic->getCard());

            return $this->render('RestaurantBundle:CashClosure:edit.html.twig', array(
                'entity_basic' => $entity_basic,
                'form_basic' => $form_basic->createView(),
                'form_total' => $form_total->createView(),
                'form_begin' => $form_begin->createView(),
                'form_bill' => $form_bill->createView(),
                'form_card' => $form_card->createView(),
                'show' => TRUE,
            ));
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }

    /**
     * 
     *
     * @Route("/{id}/finish/", name="cashclosure_finish")
     * @Template()
     */
    public function finishAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity_basic = $em->getRepository('RestaurantBundle:CashClosure')->find($id);
        if ($entity_basic){
            $entity_basic->setFinished(new \DateTime());
            $not = $em->getRepository('AdminBundle:RNotifierForm')->findOneBy(array('form'=>$entity_basic->getId()));
            if (is_null($not)) $this->sendMail($id);
            $em->flush();
        }
        die;
    }
    

    /**
     * 
     *
     * @Route("/{id}/edit/", name="cashclosure_edit")
     * @Template()
     */
    public function editAction($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_SERVICE') {

            $request = $this->getRequest();
            $em = $this->getDoctrine()->getManager();
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $entity_basic = $em->getRepository('RestaurantBundle:CashClosure')->find($id);
            if (!$entity_basic) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
            }
            $olddate = new \DateTime('today - 1 day');
            $now = new \DateTime('now');
            //if (strtotime($olddate->format('d-m-Y')) > strtotime($entity_basic->getUpdated()->format('d-m-Y')) && $user->getRole() != 'ROLE_SUPERADMIN') {
            //if (($olddate->format('d-m-Y') == $entity_basic->getDated()->format('d-m-Y') && ($now->format('G') >= 8) || $entity_basic->getDated()->format('d-m-Y') != $now->format('d-m-Y') ) && $user->getRole() != 'ROLE_SUPERADMIN') {
            if (($entity_basic->getUpdated()->diff($now)->d >= 2) && ($user->getRole() != 'ROLE_SUPERADMIN')) {
                $this->addFlash('error', 'Error! This form can not be modified.');
                return $this->redirect($this->generateUrl('cashclosure', array('date' => date('m-Y'))));
            }

            $form_basic = $this->createForm(new CashClosureType(), $entity_basic);
            $form_total = $this->createForm(new CashClosureTotalType(), $entity_basic->getTotal());
            $form_bill = $this->createForm(new BillType(), $entity_basic->getBill());
            $form_begin = $this->createForm(new BeginBillType(), $entity_basic->getBeginbill());
            $form_card = $this->createForm(new CardType(), $entity_basic->getCard());
            $response = new JsonResponse();
            // FIXED FOR LIANA

           // if ($entity_basic->getFinished() != null){
                $reception = $em->getRepository('RestaurantBundle:Reception')->findOneBy(array('dated' => $entity_basic->getDated()));
                if (!is_null($reception)) {
                    $entity_basic->setVoucherday($reception->getVdamount());
                    $entity_basic->setVouchernight($reception->getVnamount());
                }
                $em->persist($entity_basic);
                $em->flush();
            //}
            if ($request->getMethod() == 'POST') {
                $form_basic->handleRequest($request);
                $form_total->handleRequest($request);
                $form_begin->handleRequest($request);
                $form_bill->handleRequest($request);
                $form_card->handleRequest($request);

                try {
                    //if ($form_basic->isValid() ) {
                    $entity_basic->setUpdated(new \DateTime('today'));

                    $entity_basic->setTotal($this->validateFormTotal($entity_basic->getTotal()));
                    $entity_basic->setBill($this->validateFormBill($entity_basic->getBill()));
                    $entity_basic->setBeginbill($this->validateFormBegin($entity_basic->getBeginbill()));
                    $entity_basic->setCard($this->validateFormCard($entity_basic->getCard()));
                    $not = $em->getRepository('AdminBundle:RNotifierForm')->findOneBy(array('form' => $entity_basic->getId()));
                    $reception = $em->getRepository('RestaurantBundle:Reception')->findOneBy(array('dated' => $entity_basic->getDated()));
                    if (!is_null($reception)) {
                        $entity_basic->setVoucherday($reception->getVdamount());
                        $entity_basic->setVouchernight($reception->getVnamount());
                    }
                    $em->persist($entity_basic);
                    //$em->flush();
                    $em->flush();
                    $response->setData('true');
                    // }else $response->setData('false');
                } catch (\Exception $ex) {
                    $response = new JsonResponse();
                    $response->setData($ex);
                    return $response;
                }
                return $response;
            } else {
                return $this->render('RestaurantBundle:CashClosure:edit.html.twig', array(
                    'entity_basic' => $entity_basic,
                    'form_basic' => $form_basic->createView(),
                    'form_total' => $form_total->createView(),
                    'form_begin' => $form_begin->createView(),
                    'form_bill' => $form_bill->createView(),
                    'form_card' => $form_card->createView(),
                    'show' => FALSE,
                ));
            }
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }

    /**
     * @Route("/{id}/delete/", name="cashclosure_delete")
     * @Template()
     */
    public function deleteAction($id) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('RestaurantBundle:CashClosure')->find($id);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_SERVICE') {

            if (!$entity) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
            }
            $now = new \DateTime('now');
            //echo  $entity_basic->getUpdated()->diff($now)->d ; die;
            //if (strtotime($olddate->format('d-m-Y')) > strtotime($entity_basic->getUpdated()->format('d-m-Y')) && $user->getRole() != 'ROLE_SUPERADMIN') {
            if (($entity->getUpdated()->diff($now)->d >= 2) && ($user->getRole() != 'ROLE_SUPERADMIN')) {
                $this->addFlash('error', 'Error! This form can not be removed.');
                return $this->redirect($this->generateUrl('cashclosure', array('date' => date('m-Y'))));
            }
            try {
                $em->remove($entity->getTotal());
                $em->remove($entity->getBill());
                $em->remove($entity->getBeginbill());
                $em->remove($entity->getCard());
                $em->remove($entity);
                $em->flush();
            } catch (\Exception $ex) {
                return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
            }
            $this->addFlash('success', 'Success! The form has been removed.');
            return $this->redirect($this->generateUrl('cashclosure', array('date' => date('m-Y'))));
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }

    private function sendMail($id){
        $em = $this->getDoctrine()->getManager();

        //Crear el notificador para este formulario.
        $notifier = $em->getRepository('AdminBundle:Notifier')->findOneBy(array('form'=>'Service'));
        $entity_basic = $em->getRepository('RestaurantBundle:CashClosure')->findOneBy(array('id'=>$id));
        $mails_array = explode(';',$notifier->getMails());
        $mail_customer = \Swift_Message::newInstance()
            ->setFrom('info@log.towerleisure.nl')
            ->setTo($mails_array)
            ->setSubject("Service Kassa Cash & Log")
            //->setBody('prueba')
            ->setBody($this->renderView('RestaurantBundle:CashClosure:mail.html.twig', array(
                'entity_basic' => $entity_basic,
                'giftvouchers' => (array) json_decode($entity_basic->getGiftvouchersvalues()),
                'extvouchers' => (array) json_decode($entity_basic->getExtvouchersvalues()),
                'voorgeschoten' => (array) json_decode($entity_basic->getVoorgeschoten()),
                'voorverkoop' => (array) json_decode($entity_basic->getVoorverkoop()),
                'rekening' => (array) json_decode($entity_basic->getRekening()),)))
            ->setContentType("text/html");

        $this->get('mailer')->send($mail_customer);
        $mailer = new RNotifierForm();
        $mailer->setNotifier($notifier);
        $mailer->setForm($id);
        $mailer->setBody((string)$mail_customer->getBody());
        $mailer->setDate(new \DateTime('now'));
        $mailer->setSubject("Service Kassa Cash & Log");
        $mailer->setTo($notifier->getMails());
        //Obtener el status del servidor de correo.
        $mailer->setStatus('Enviado');
        $em->persist($mailer);

        // Extra
        $lastmail = $em->getRepository('AdminBundle:RNotifierForm')->findOneBy(array('form'=>$entity_basic->getId(), 'to'=>$notifier->getExternals()), array('date'=>'DESC'));
        //if (!is_null($notifier->getExternals()) && $lastmail != null && $entity_basic->getUpdated() > $lastmail->getDate()){
        if (!is_null($notifier->getExternals())){
            $mails_array = explode(';',$notifier->getExternals());
            $mail_customer = \Swift_Message::newInstance()
                ->setFrom('info@log.towerleisure.nl')
                ->setTo($mails_array)
                ->setSubject("Op Rekening - Service Kassa Cash & Log")
                ->setBody($this->renderView('RestaurantBundle:CashClosure:externalmail.html.twig', array(
                    'entity_basic' => $entity_basic,
                    'rekening' => (array) json_decode($entity_basic->getRekening()),)))
                ->setContentType("text/html");

            $this->get('mailer')->send($mail_customer);
            $mailer = new RNotifierForm();
            $mailer->setNotifier($notifier);
            $mailer->setForm($id);
            $mailer->setBody((string)$mail_customer->getBody());
            $mailer->setDate(new \DateTime('now'));
            $mailer->setSubject("Op Rekening - Service Kassa Cash & Log");
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
     * @Route("/{id}/mail/", name="cashclosure_mail")
     */
    public function mailAction($id){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER' || $user->getRole() == 'ROLE_SERVICE') {

            try {
                $this->sendMail($id);
            } catch (\Exception $ex) {
                return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
            }
            $em = $this->getDoctrine()->getManager();
            $entity_basic = $em->getRepository('RestaurantBundle:CashClosure')->findOneBy(array('id' => $id));
            $date = $entity_basic->getDated()->format('m-Y');
            $this->addFlash('success', 'Success! The form has been sent.');
            return $this->redirect($this->generateUrl('cashclosure', array('date' => $date)));
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }

    /**
     *
     * @Route("/voucherdate/", name="cashclosure_voucher_date")
     */
    public function voucherDateAction(Request $request){
        $response = new JsonResponse();
        if ($request->getMethod() == 'POST') {
            $date = $request->get('date');
            $em = $this->getDoctrine()->getManager();
            $formated = new \DateTime($date);
            $a = $formated->format('Y-m-d');
            $reception = $em->getRepository('RestaurantBundle:Reception')->findOneBy(array('dated' => $a));
            if (!$reception) {
                $response->setData(json_encode(array(
                    'day' => '',
                    'night' => '',
                )));
            }
            $response->setData(json_encode(array(
                'day' => $reception->getVdamount(),
                'night' => $reception->getVnamount(),
            )));
        }
        die('lolo');

    }

}
