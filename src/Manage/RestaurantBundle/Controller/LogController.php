<?php

namespace Manage\RestaurantBundle\Controller;

use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\Log;
use Manage\AdminBundle\Entity\WorkerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Manage\AdminBundle\Entity\RNotifierForm;

/**
 * Log controller.
 *
 * @Route("/log")
 */
class LogController extends Controller {

    /**
     * Lists all Listing entities.
     *
     * @Route("/date/{date}/", name="log")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($date) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER') {
            $partes = explode('-', $date);
            $date = $partes[1] . '-' . $partes[0];

            $em = $this->getDoctrine()->getManager();
            $consulta = $em->createQuery('SELECT r FROM RestaurantBundle:Log r WHERE r.dated >= \'' . $date . '-01\' AND r.dated <= \'' . $date . '-31\' ORDER BY r.dated DESC');
            $entities = $consulta->getResult();
            $consulta = $em->createQuery('SELECT r.form, count(r.id) AS cantidad FROM AdminBundle:RNotifierForm r JOIN r.notifier n WHERE n.form LIKE \'Log\' GROUP BY r.form');
            $notifier = $consulta->getResult();
            $result = array();
            foreach ($entities as $entity) {
                foreach ($notifier as $not) {
                    if ($not['form'] == $entity->getId()) {
                        $result[] = $not;
                    }
                }
            }

            return array(
                'entities' => $entities,
                'notifier' => $result,
            );
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }

    /**
     * Displays a form to create a new Listing entity.
     *
     * @Route("/new/", name="log_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER') {
            $entity_log = new Log();
            $em = $this->getDoctrine()->getManager();
            $entity_log->setDated(new \DateTime('today'));
            $entity_log->setUpdated(new \DateTime('today'));
            $em->persist($entity_log);
            $em->flush();
            return $this->redirect($this->generateUrl('log_edit', array('id' => $entity_log->getId())));
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }
    
    private function getUsers() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AdminBundle:User')->findAll();
        $result = array();
        foreach ($entities as $item) {
            $result[] = array(
                "id" => $item->getId(),
                "name" => $item->getName(),
                "email" => $item->getEmail(),
            );
        }
        return $result;
    }

    /**
     * Finds and displays a Listing entity.
     *
     * @Route("/{id}/", name="log_show")
     * @Method("GET")
     * @Template()
     */
    
    public function showAction(Request $request, $id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER') {
            $em = $this->getDoctrine()->getManager();
            $entity_basic = $em->getRepository('RestaurantBundle:Log')->find($id);
            if (!$entity_basic) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
            }

            return $this->render('RestaurantBundle:Log:edit.html.twig', array(
                'entity_basic' => $entity_basic,
                'list_chef' => $em->getRepository('AdminBundle:Worker')->getChefs(),
                'list_manager' => $em->getRepository('AdminBundle:Worker')->getManagers(),
                'list_recepties' => $em->getRepository('AdminBundle:Worker')->getRecepties(),
                'show' => TRUE,
            ));
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }

    /**
     * Displays a form to edit an existing Listing entity.
     *
     * @Route("/{id}/edit/", name="log_edit")
     * @Template()
     */
    public function editAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $entity_basic = $em->getRepository('RestaurantBundle:Log')->find($id);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER') {
            if (!$entity_basic) {
                return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
            }
            $olddate = new \DateTime('today - 1 day');
            $now = new \DateTime('now');
            //if (strtotime($olddate->format('d-m-Y')) > strtotime($entity_basic->getUpdated()->format('d-m-Y')) && $user->getRole() != 'ROLE_SUPERADMIN') {
            //if (($olddate->format('d-m-Y') == $entity_basic->getDated()->format('d-m-Y') && ($now->format('G') >= 8) || $entity_basic->getDated()->format('d-m-Y') != $now->format('d-m-Y') ) && $user->getRole() != 'ROLE_SUPERADMIN') {

            if (($entity_basic->getUpdated()->diff($now)->d >= 2) && ($user->getRole() != 'ROLE_SUPERADMIN')) {
                $this->addFlash('error', 'Error! This form can not be modified.');
                return $this->redirect($this->generateUrl('log', array('date' => date('m-Y'))));
            }
            $entity_basic->setUpdated(new \DateTime('today'));
            if ($request->getMethod() == 'POST') {
                $data = $request->get('data');
                try {
                    foreach ($data['form-log'] as $item) {
                        switch ($item['name']) {
                            case 'dated':
                                $entity_basic->setDated(new \DateTime($item['value']));
                                break;
                            case 'chef':
                                $worker = $item['value'] != null ? $em->getRepository('AdminBundle:Worker')->find($item['value']) : null;
                                if ($worker != null) $entity_basic->setChef($worker);
                                break;
                            case 'managerdag':
                                $worker = $item['value'] != null ? $em->getRepository('AdminBundle:Worker')->find($item['value']) : null;
                                if ($worker != null) $entity_basic->setManagerdag($worker);
                                break;
                            case 'manageravond':
                                $worker = $item['value'] != null ? $em->getRepository('AdminBundle:Worker')->find($item['value']) : null;
                                if ($worker != null) $entity_basic->setManageravond($worker);
                                break;
                            case 'receptiedag':
                                $worker = $item['value'] != null ? $em->getRepository('AdminBundle:Worker')->find($item['value']) : null;
                                if ($worker != null) $entity_basic->setReceptiedag($worker);
                                break;
                            case 'receptieavond':
                                $worker = $item['value'] != null ? $em->getRepository('AdminBundle:Worker')->find($item['value']) : null;
                                if ($worker != null) $entity_basic->setReceptieavond($worker);
                                break;
                            case 'closetime':
                                $entity_basic->setClosetime(new \DateTime($item['value']));
                                break;
                            case 'lastguesttime':
                                $entity_basic->setLastguesttime(new \DateTime($item['value']));
                                break;
                            case 'finished':
                                break;
                            default:
                                $upper = strtoupper(substr($item['name'], 0, 1));
                                $rest = substr($item['name'], 1);
                                $set_method = 'set' . $upper . $rest;
                                $entity_basic->$set_method($item['value']);
                        }
                    }
                    if (isset($data['final']) && $data['final'] == 'true') $entity_basic->setFinished(new \DateTime());
                    $em->persist($entity_basic);
                    $em->flush();
                    $response = new JsonResponse();
                    $response->setData('true');

                } catch (\Exception $ex) {
                    $response = new JsonResponse();
                    $response->setData($ex);
                    return $response;
                }
                return $response;
            } else {
                return $this->render('RestaurantBundle:Log:edit.html.twig', array(
                    'entity_basic' => $entity_basic,
                    'list_chef' => $em->getRepository('AdminBundle:Worker')->getChefs(),
                    'list_manager' => $em->getRepository('AdminBundle:Worker')->getManagers(),
                    'list_recepties' => $em->getRepository('AdminBundle:Worker')->getRecepties(),
                    'show' => FALSE,
                ));
            }
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }

    /**
     * Deletes a Listing entity.
     *
     * @Route("/{id}/delete/", name="log_delete")
     * @Method("GET")
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('RestaurantBundle:Log')->find($id);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER') {
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Form.');
            }
            $now = new \DateTime('now');
            //echo  $entity_basic->getUpdated()->diff($now)->d ; die;
            //if (strtotime($olddate->format('d-m-Y')) > strtotime($entity_basic->getUpdated()->format('d-m-Y')) && $user->getRole() != 'ROLE_SUPERADMIN') {
            if (($entity->getUpdated()->diff($now)->d >= 2) && ($user->getRole() != 'ROLE_SUPERADMIN')) {
                $this->addFlash('error', 'Error! This form can not be removed.');
                return $this->redirect($this->generateUrl('log', array('date' => date('m-Y'))));
            }
            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Success! This form was removed.');

            return $this->redirect($this->generateUrl('log', array('date' => date('m-Y'))));
        }
        return $this->render('AdminBundle:Exception:error403.html.twig');

    }

    private function sendMail($id){
        $em = $this->getDoctrine()->getManager();

        //Crear el notificador para este formulario.
        $notifier = $em->getRepository('AdminBundle:Notifier')->findOneBy(array('form'=>'Log'));
        $entity_basic = $em->getRepository('RestaurantBundle:Log')->findOneBy(array('id'=>$id));
        $mails_array = explode(';',$notifier->getMails());
        $mail_customer = \Swift_Message::newInstance()
            ->setFrom('info@log.towerleisure.nl')
            ->setTo($mails_array)
            ->setSubject("Logboek Keuken, Restaurant, Receptie & Hotel")

            ->setBody($this->renderView('RestaurantBundle:Log:mail.html.twig', array(
                'entity_basic' => $entity_basic,)))
            ->setContentType("text/html");

        $this->get('mailer')->send($mail_customer);
        $mailer = new RNotifierForm();
        $mailer->setNotifier($notifier);
        $mailer->setForm($id);
        $mailer->setBody((string)$mail_customer->getBody());
        $mailer->setDate(new \DateTime('now'));
        $mailer->setSubject("Logboek Keuken, Restaurant, Receptie & Hotel");
        $mailer->setTo($notifier->getMails());
        //Obtener el status del servidor de correo.
        $mailer->setStatus('Enviado');
        $em->persist($mailer);
        if (!is_null($notifier->getExternals())){
            $mails_array = explode(';',$notifier->getExternals());
            $mail_customer = \Swift_Message::newInstance()
                ->setFrom('info@log.towerleisure.nl')
                ->setTo($mails_array)
                ->setSubject("Logboek Keuken, Restaurant, Receptie & Hotel")
                ->setBody($this->renderView('RestaurantBundle:Log:mail.html.twig', array(
                    'entity_basic' => $entity_basic,
                )))
                ->setContentType("text/html");

            $this->get('mailer')->send($mail_customer);
            $mailer = new RNotifierForm();
            $mailer->setNotifier($notifier);
            $mailer->setForm($id);
            $mailer->setBody((string)$mail_customer->getBody());
            $mailer->setDate(new \DateTime('now'));
            $mailer->setSubject("Logboek Keuken, Restaurant, Receptie & Hotel");
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
     * @Route("/{id}/mail/", name="log_mail")
     */
    public function mailAction($id){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole() == 'ROLE_SUPERADMIN' || $user->getRole() == 'ROLE_MANAGER') {
            try {
                $this->sendMail($id);
            } catch (\Exception $ex) {
                return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
            }
            $this->addFlash('success', 'Success! The form has been sent.');
            return $this->redirect($this->generateUrl('log', array('date' => date('m-Y'))));
        }

        return $this->render('AdminBundle:Exception:error403.html.twig');

    }
    
}
