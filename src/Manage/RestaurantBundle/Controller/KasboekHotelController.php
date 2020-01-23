<?php

namespace Manage\RestaurantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\KasboekHotel;
use Manage\RestaurantBundle\Entity\KasboekHotelFloat;
use Manage\RestaurantBundle\Entity\KasboekHotelForms;
use Manage\RestaurantBundle\Form\KasboekHotelType;
use Manage\RestaurantBundle\Form\KasboekHotelFloatType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Manage\RestaurantBundle\Entity\RNotifierForm;
use Symfony\Component\Validator\Constraints\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * KasboekHotel controller.
 *
 * @Route("/kasboekhotel")
 */
class KasboekHotelController extends Controller {

    /**
     *
     * @Route("/new/", name="kasboekhotel_new")
     * @Security("is_granted('ROLE_HOTEL_KASBOEK')")
     * @Template()
     */
    public function newAction() {
        $entity_basic = new KasboekHotel();
        $entity_basic->setDated(new \DateTime());
        $entity_basic->setUpdated(new \DateTime());
        
        $em = $this->getDoctrine()->getManager();
        
        $float = new KasboekHotelFloat();

        $em->persist($float);
        $entity_basic->setFloat($float);

        $em->persist($entity_basic);
        $em->flush();

        //Crear las relaciones con los datos dinámicos de turnover en kasboek
        for($i = 1; $i <= 31 && checkdate($entity_basic->getDated()->format('n'),$i, $entity_basic->getDated()->format('Y')); $i ++){
            $form = new KasboekHotelForms();
            $form->setKasboekhotel($entity_basic);
            $form->setDay($i);
            $em->persist($form);
        }
        $em->flush();

        $entity_basic = $this->updateCalculos($entity_basic);
        return $this->redirect($this->generateUrl('kasboekhotel_edit', array('id' => $entity_basic->getId())));
    }

   
    /**
     * Lists all KasboekHotel entities.
     *
     * @Route("/date/{date}/", name="kasboekhotel")
     * @Method("GET")
     * @Security("is_granted('ROLE_HOTEL_KASBOEK')")
     * @Template()
     */
    public function indexAction($date) {
        $partes = explode('-', $date);
        $date = $partes[1].'-'.$partes[0];

        $em = $this->getDoctrine()->getManager();
        //$entities = $em->getRepository('RestaurantBundle:KasboekHotel')->findBy(array('dated'=>'> 2018-01-01', 'dated'=>'< 2018-01-31'), array('dated'=>'DESC'));
        $consulta = $em->createQuery('SELECT r FROM RestaurantBundle:KasboekHotel r WHERE r.dated >= \''.$date.'-01\' AND r.dated <= \''.$date.'-31\' ORDER BY r.dated DESC');
        $entities = $consulta->getResult();

        $consulta = $em->createQuery('SELECT r.form, count(r.id) AS cantidad FROM RestaurantBundle:RNotifierForm r JOIN r.notifier n WHERE n.form LIKE \'KasboekHotel\' GROUP BY r.form');
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


    /**
     * Finds and displays a KasboekHotel entity.
     *
     * @Route("/{id}/", name="kasboekhotel_show")
     * @Security("is_granted('ROLE_HOTEL_KASBOEK')")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $entity_basic = $em->getRepository('RestaurantBundle:KasboekHotel')->find($id);
        if (!$entity_basic) {
            return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
        }

        $form_basic = $this->createForm(new KasboekHotelType(), $entity_basic);
        $form_float = $this->createForm(new KasboekHotelFloatType(), $entity_basic->getFloat());

        $fecha = $entity_basic->getDated()->format('Y-m-d');
        $fecha_array = explode('-', $fecha);
        return $this->render('RestaurantBundle:KasboekHotel:edit.html.twig', array(
            'entity_basic' => $entity_basic,
            'form_basic' => $form_basic->createView(),
            'form_float' => $form_float->createView(),
            'hotel1'  => $this->getFormsHotel(1, $fecha_array[1], $fecha_array[0]),
            'hotel2'  => $this->getFormsHotel(2, $fecha_array[1], $fecha_array[0]),
            'hotel3'  => $this->getFormsHotel(3, $fecha_array[1], $fecha_array[0]),
            'hotel4'  => $this->getFormsHotel(4, $fecha_array[1], $fecha_array[0]),
            'hotel5'  => $this->getFormsHotel(5, $fecha_array[1], $fecha_array[0]),
            'show'  => TRUE,
        ));
    }

    /**
     * Finds and displays a KasboekHotel entity.
     * @Security("is_granted('ROLE_HOTEL_KASBOEK')")
     * @Route("/{id}/delete/", name="kasboekhotel_delete")
     * @Template()
     */
    public function deleteAction($id) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:KasboekHotel')->find($id);
            $user = $this->get('security.token_storage')->getToken()->getUser();
            if (!$entity) {
                return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
            }
            $now = new \DateTime('now');
            //echo  $entity_basic->getUpdated()->diff($now)->d ; die;
            //if (strtotime($olddate->format('d-m-Y')) > strtotime($entity_basic->getUpdated()->format('d-m-Y')) && $user->getRole() != 'ROLE_SUPERADMIN') {
            if (($entity->getUpdated()->diff($now)->d >= 2) && (!$this->isGranted('ROLE_SUPER_ADMIN'))) {
                $this->addFlash('error', 'Error! This form can not be removed.');
                return $this->redirect($this->generateUrl('kasboekhotel', array('date' => date('m-Y'))));
            }
            try {
                $em->remove($entity->getFloat());
                $turn = $em->getRepository('RestaurantBundle:KasboekHotelForms')->findBy(array('kasboekhotel' => $entity->getId()));
                foreach ($turn as $item) {
                    $em->remove($item);
                }
                $em->remove($entity);
                $em->flush();
            } catch (\Exception $ex) {
                return $this->render('RestaurantBundle:Exception:exception.html.twig', array('message' => $ex));
            }
            $this->addFlash('success', 'Success! The form has been removed.');
            return $this->redirect($this->generateUrl('kasboekhotel', array('date' => date('m-Y'))));
    }

    /**
     * @Security("is_granted('ROLE_HOTEL_KASBOEK')")
     * @Route("/{id}/edit/", name="kasboekhotel_edit")
     */
    public function editAction($id) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $user=$this->get('security.token_storage')->getToken()->getUser();

        $entity_basic = $em->getRepository('RestaurantBundle:KasboekHotel')->find($id);

        if (!$entity_basic) {
            return $this->render('RestaurantBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
        }

            //{% if 'today - 3 day' | date('d-m-Y') < entity.updated | date('d-m-Y') %}
        $now = new \DateTime('now');

        //if (strtotime($olddate->format('d-m-Y')) > strtotime($entity_basic->getUpdated()->format('d-m-Y')) && $user->getRole()!='ROLE_SUPERADMIN' && $user->getRole()!='ROLE_MANAGER'){
        if (($entity_basic->getUpdated()->diff($now)->d >= 15) && ($this->isGranted('ROLE_SUPER_ADMIN'))) {
            $this->addFlash('error', 'Error! This form can not be modified.');
            return $this->redirect($this->generateUrl('kasboekhotel', array('date'=>date('m-Y'))));
        }
        $entity_modif = $this->updateCalculos($entity_basic);
        $form_basic = $this->createForm(new KasboekHotelType(), $entity_modif);
        $form_float = $this->createForm(new KasboekHotelFloatType(), $entity_modif->getFloat());

        $response = new JsonResponse();
        if ($request->getMethod() == 'POST') {
            $form_basic->handleRequest($request);
            $form_float->handleRequest($request);
            try {
                 $entity_modif->setUpdated(new \DateTime());

                if (!is_null($request->get('bank'))){
                    $entity_modif->getFloat()->setBank($request->get('bank'));
                }
                    $em->persist($entity_modif);
                    $em->flush();

                    $response->setData('true');
                    return $response;

            } catch (\Exception $ex) {
                $response->setData($ex->getMessage());
                return $response;
            }
            return $response;
        } else {
            $fecha = $entity_modif->getDated()->format('Y-m-d');
            $fecha_array = explode('-', $fecha);
            //$entity_basic = $this->updateCalculos($entity_basic);
            return $this->render('RestaurantBundle:KasboekHotel:edit.html.twig', array(
                'entity_basic' => $entity_modif,
                'form_basic' => $form_basic->createView(),
                'form_float' => $form_float->createView(),
                'hotel1'  => $this->getFormsHotel(1, $fecha_array[1], $fecha_array[0]),
                'hotel2'  => $this->getFormsHotel(2, $fecha_array[1], $fecha_array[0]),
                'hotel3'  => $this->getFormsHotel(3, $fecha_array[1], $fecha_array[0]),
                'hotel4'  => $this->getFormsHotel(4, $fecha_array[1], $fecha_array[0]),
                'hotel5'  => $this->getFormsHotel(5, $fecha_array[1], $fecha_array[0]),
                'show'  => FALSE,
            ));
        }
    }

    //Obtener los formularios turnover por fechas. Rangos de 7 días

    private function getFormsHotel($rango, $mes, $anno){
        $em = $this->getDoctrine()->getManager();
        $entities = array();
        $control = false;
        switch ($rango){
            case 1:
                for ($i = 1; $i <= 7; $i++){
                    $result = $this->getDoctrine()->getRepository('RestaurantBundle:Hotel')->createQueryBuilder('h')
                        ->where('h.dated = :fecha')
                        ->andWhere("(h.finished IS NOT NULL OR h.finished != :vacio) AND h.name='true'")
                        ->setMaxResults(1)
                        ->setParameter('fecha', new \DateTime($anno.'-'.$mes.'-'.$i))
                        ->setParameter('vacio', '')
                        ->getQuery()
                        ->getResult();

                    if (count($result) == 1){
                        $control = true;
                        $entities[] = $result[0];
                    } else $entities[] = null;
                }
                break;
            case 2:
                for ($i = 8; $i <= 14; $i++){
                    $result = $this->getDoctrine()->getRepository('RestaurantBundle:Hotel')->createQueryBuilder('h')
                        ->where('h.dated = :fecha')
                        ->andWhere("(h.finished IS NOT NULL OR h.finished != :vacio) AND h.name='true'")
                        ->setMaxResults(1)
                        ->setParameter('fecha', new \DateTime($anno.'-'.$mes.'-'.$i))
                        ->setParameter('vacio', '')
                        ->getQuery()
                        ->getResult();
                    if (count($result) == 1){
                        $control = true;
                        $entities[] = $result[0];
                    } else $entities[] = null;
                }
                break;
            case 3:
                for ($i = 15; $i <= 21; $i++){
                    $result = $this->getDoctrine()->getRepository('RestaurantBundle:Hotel')->createQueryBuilder('h')
                        ->where('h.dated = :fecha')
                        ->andWhere("(h.finished IS NOT NULL OR h.finished != :vacio) AND h.name='true'")
                        ->setMaxResults(1)
                        ->setParameter('fecha', new \DateTime($anno.'-'.$mes.'-'.$i))
                        ->setParameter('vacio', '')
                        ->getQuery()
                        ->getResult();
                    if (count($result) == 1){
                        $control = true;
                        $entities[] = $result[0];
                    } else $entities[] = null;
                }
                break;
            case 4:
                for ($i = 22; $i <= 28; $i++){
                    $result = $this->getDoctrine()->getRepository('RestaurantBundle:Hotel')->createQueryBuilder('h')
                        ->where('h.dated = :fecha')
                        ->andWhere("(h.finished IS NOT NULL OR h.finished != :vacio) AND h.name='true'")
                        ->setMaxResults(1)
                        ->setParameter('fecha', new \DateTime($anno.'-'.$mes.'-'.$i))
                        ->setParameter('vacio', '')
                        ->getQuery()
                        ->getResult();
                    if (count($result) == 1){
                        $control = true;
                        $entities[] = $result[0];
                    } else $entities[] = null;
                }
                break;
            case 5:
                for ($i = 29; $i <= 31 && checkdate((integer)$mes,$i, $anno); $i++){
                    $result = $this->getDoctrine()->getRepository('RestaurantBundle:Hotel')->createQueryBuilder('h')
                        ->where('h.dated = :fecha')
                        ->andWhere("(h.finished IS NOT NULL OR h.finished != :vacio) AND h.name='true'")
                        ->setMaxResults(1)
                        ->setParameter('fecha', new \DateTime($anno.'-'.$mes.'-'.$i))
                        ->setParameter('vacio', '')
                        ->getQuery()
                        ->getResult();
                    if (count($result) == 1){
                        $control = true;
                        $entities[] = $result[0];
                    } else $entities[] = null;
                }
                break;
        }
        return $entities ;
    }

    //A partir de la entidad KasboekHotel se realizan todos los cálculos correspondientes
    private function updateCalculos($entity_kasboek){
        $str_date = $entity_kasboek->getDated()->format('Y-m');

        $em = $this->getDoctrine()->getManager();
        $hotels = array();
        //echo var_dump(checkdate($entity_kasboek->getDated()->format('n'),31, $entity_kasboek->getDated()->format('Y')));die;

        for ($i = 1; $i <= 31 && checkdate($entity_kasboek->getDated()->format('n'),$i, $entity_kasboek->getDated()->format('Y')); $i++){
            if (true){

                $result = $this->getDoctrine()->getRepository('RestaurantBundle:Hotel')->createQueryBuilder('h')
                    ->where('h.dated = :fecha')
                    ->andWhere("(h.finished IS NOT NULL OR h.finished != :vacio) AND h.name='true'")
                    ->setMaxResults(1)
                    ->setParameter('fecha', new \DateTime($str_date.'-'.$i))
                    ->setParameter('vacio', '')
                    ->getQuery()
                    ->getResult();
                if (count($result) == 1)
                    $hotels[] = $result[0];
            }


        }
        //$turnovers = $em->getRepository('RestaurantBundle:Turnover')->findAll(array('dated'=>new \DateTime($str_date)));
        $calcs = array();
        $calcs['overnachtingen'] = 0;
        $calcs['parking']  = 0;
        $calcs['toeristenbelasting']  = 0;
        $calcs['totaalborg']  = 0;
        $calcs['totaalont']  = 0;
        $calcs['contanten']  = 0;
        $calcs['debit']  = 0;
        $calcs['credit']  = 0;
        $calcs['totaalnaar']  = 0;
        $calcs['kasverschil']= 0;

        foreach ($hotels as $hotel) {
            $calcs['overnachtingen'] +=  ($hotel->getTotalover() + $hotel->getTotalextra() - $hotel->getTotalvoldan());
            $calcs['parking']  +=  $hotel->getTotalparking();
            $calcs['toeristenbelasting']  +=  $hotel->getTotaltoer();
            $calcs['totaalborg']  +=  $hotel->getSaldoborg();
            //(item.totalover + item.totaltoer + item.totalparking)
            $calcs['totaalont']  +=  ($hotel->getTotalover() + $hotel->getTotalextra() + $hotel->getTotaltoer() + $hotel->getTotalparking() + $hotel->getSaldoborg() - $hotel->getTotalvoldan()) ;
            $calcs['contanten']  += $hotel->getTotalcontanten();
            $calcs['debit']  +=  $hotel->getTotaldebit();
            $calcs['credit']  +=  $hotel->getTotalcredit();
            //(item.totalcontanten+ item.totaldebit + item.totalcredit)
            $calcs['totaalnaar'] += ($hotel->getTotalcontanten() + $hotel->getTotaldebit() + $hotel->getTotalcredit());
            //$calcs['kasverschil'] += (($hotel->getTotalcontanten() + $hotel->getTotaldebit() + $hotel->getTotalcredit()) - ($hotel->getTotalover() + $hotel->getTotaltoer() + $hotel->getTotalparking()));
        }


        $entity_kasboek->setOvernachtingen($calcs['overnachtingen']);
        $entity_kasboek->setParking($calcs['parking']);
        $entity_kasboek->setToeristenbelasting($calcs['toeristenbelasting']);
        $entity_kasboek->setTotaalborg($calcs['totaalborg']);
        $entity_kasboek->setTotaalont($calcs['totaalont']);
        $entity_kasboek->setContanten($calcs['contanten']);
        //$entity_kasboek->getFloat()->setContant($calcs['contanten']);
        $entity_kasboek->setDebit($calcs['debit']);
        $entity_kasboek->setCredit($calcs['credit']);
        $entity_kasboek->setTotaalnaar($calcs['totaalnaar']);
        $entity_kasboek->setKasverschil($calcs['totaalnaar'] - $calcs['totaalont']);

        $total = 0;
        $float = $entity_kasboek->getFloat();
        $total += $float->getE500() * 500;
        $total += $float->getE200() * 200;
        $total += $float->getE100() * 100;
        $total += $float->getE50() * 50;
        $total += $float->getE20() * 20;
        $total += $float->getE10() * 10;
        $total += $float->getE5() * 5;

        $float->setTotalmoney($total+$float->getWaarde());
        $fullbank = $float->getBank();
        $bank = 0;
        if (is_array($fullbank)){
            foreach ($fullbank as $b)
                $bank += $b;
        }
        $float->setContant($total + $float->getWaarde() + $float->getBank1() + $float->getBank2() + $float->getBank3() + $float->getBank4() + $bank);
        //$float->setKasverschil($total + $float->getWaarde() - $calcs['contanten']);

        $float->setKasverschil($float->getContant() - $calcs['contanten']);
        $entity_kasboek->setFloat($float);
        $em->persist($entity_kasboek);
        $em->flush();

        return $entity_kasboek; 
    }

    private function sendMail($id){
        $em = $this->getDoctrine()->getManager();

        //Crear el notificador para este formulario.
        $notifier = $em->getRepository('RestaurantBundle:Notifier')->findOneBy(array('form'=>'KasboekHotel'));
        $entity_basic = $em->getRepository('RestaurantBundle:KasboekHotel')->findOneBy(array('id'=>$id));
        $fecha = $entity_basic->getDated()->format('Y-m-d');
        $fecha_array = explode('-', $fecha);
        $form_basic = $this->createForm(new KasboekHotelType(), $entity_basic);
        $form_float = $this->createForm(new KasboekHotelFloatType(), $entity_basic->getFloat());
        $mails_array = explode(';',$notifier->getMails());
        $mail_customer = \Swift_Message::newInstance()
            ->setFrom('info@log.towerleisure.nl')
            ->setTo($mails_array)
            ->setSubject("Kasboek Hotel")
            ->setBody($this->renderView('RestaurantBundle:KasboekHotel:mail.html.twig', array(
                'entity_basic' => $entity_basic,
                'form_basic' => $form_basic->createView(),
                'form_float' => $form_float->createView(),
                'hotel1'  => $this->getFormsHotel(1, $fecha_array[1], $fecha_array[0]),
                'hotel2'  => $this->getFormsHotel(2, $fecha_array[1], $fecha_array[0]),
                'hotel3'  => $this->getFormsHotel(3, $fecha_array[1], $fecha_array[0]),
                'hotel4'  => $this->getFormsHotel(4, $fecha_array[1], $fecha_array[0]),
                'hotel5'  => $this->getFormsHotel(5, $fecha_array[1], $fecha_array[0]),
                'show'  => FALSE,
            )))
            ->setContentType("text/html");

        $this->get('mailer')->send($mail_customer);
        $mailer = new RNotifierForm();
        $mailer->setNotifier($notifier);
        $mailer->setForm($id);
        $mailer->setBody((string)$mail_customer->getBody());
        $mailer->setDate(new \DateTime('now'));
        $mailer->setSubject("Kasboek Hotel");
        $mailer->setTo($notifier->getMails());
        //Obtener el status del servidor de correo.
        $mailer->setStatus('Enviado');
        $em->persist($mailer);
        if (!is_null($notifier->getExternals())){
            $mails_array = explode(';',$notifier->getExternals());
            $mail_customer = \Swift_Message::newInstance()
                ->setFrom('info@log.towerleisure.nl')
                ->setTo($mails_array)
                ->setSubject("Kasboek Hotel")
                ->setBody($this->renderView('RestaurantBundle:KasboekHotel:mail.html.twig', array(
                    'entity_basic' => $entity_basic,
                    'form_basic' => $form_basic->createView(),
                    'form_float' => $form_float->createView(),
                    'hotel1'  => $this->getFormsHotel(1, $fecha_array[1], $fecha_array[0]),
                    'hotel2'  => $this->getFormsHotel(2, $fecha_array[1], $fecha_array[0]),
                    'hotel3'  => $this->getFormsHotel(3, $fecha_array[1], $fecha_array[0]),
                    'hotel4'  => $this->getFormsHotel(4, $fecha_array[1], $fecha_array[0]),
                    'hotel5'  => $this->getFormsHotel(5, $fecha_array[1], $fecha_array[0]),
                    'show'  => FALSE,
                )))

                ->setContentType("text/html");

            $this->get('mailer')->send($mail_customer);
            $mailer = new RNotifierForm();
            $mailer->setNotifier($notifier);
            $mailer->setForm($id);
            $mailer->setBody((string)$mail_customer->getBody());
            $mailer->setDate(new \DateTime('now'));
            $mailer->setSubject("Kasboek Hotel");
            $mailer->setTo($notifier->getExternals());
            //Obtener el status del servidor de correo.
            $mailer->setStatus('Enviado');
            $em->persist($mailer);
        }
        $em->flush();

    }

    /**
     * Displays a form to edit an existing KasboekParking entity.
     * @Security("is_granted('ROLE_HOTEL_KASBOEK')")
     * @Route("/{id}/mail/", name="kasboekhotel_mail")
     */
    public function mailAction($id){
        try{
            $this->sendMail($id);
        } catch (\Exception $ex) {
            return $this->render('RestaurantBundle:Exception:exception.html.twig', array('message' => $ex));
        }
        $em = $this->getDoctrine()->getManager();
        $entity_basic = $em->getRepository('RestaurantBundle:KasboekHotel')->findOneBy(array('id'=>$id));
        //$date = $entity_basic->getDated()->format('m-Y');
        $this->addFlash('success', 'Success! The form has been sent.');
        return $this->redirect($this->generateUrl('kasboekhotel', array('date'=>date('m-Y'))));
    }

    /**
     *
     * @Security("is_granted('ROLE_HOTEL_KASBOEK')")
     * @Route("/kasboekhotelchangedate/{id}/", name="kasboekhotel_change_date")
     */
    public function kasboekhotelchangedateAction($id) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN' && $user->getRole()!='ROLE_MANAGER'){
            return $this->render('RestaurantBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
        $request = $this->getRequest();
        $response = new JsonResponse();

        try {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:KasboekHotel')->findOneBy(array('id' => $id));

            $entity->setDated(new \DateTime($request->get('newdate')));
            $entity->setUpdated(new \DateTime('now'));

            $em->persist($entity);
            $em->flush();
            $entity = $this->updateCalculos($entity);

            $response->setData('true');
            return $response;

        }catch (\Exception $ex) {
            $response->setData($ex);
            return $response;
        }

    }

    /**
     * @Route("/{id}/finish/", name="kasboekhotel_finish")
     * @Security("is_granted('ROLE_HOTEL_KASBOEK')")
     * @Template()
     */
    public function finishAction($id) {
        //$this->redirect('kasboekhotel_mail');
    }
}
