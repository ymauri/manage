<?php

namespace Manage\RestaurantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\Kasboek;
use Manage\RestaurantBundle\Entity\KasboekContanten;
use Manage\RestaurantBundle\Entity\KasboekFloats;
use Manage\RestaurantBundle\Entity\KasboekIn;
use Manage\RestaurantBundle\Entity\KasboekKas;
use Manage\RestaurantBundle\Entity\KasboekOut;
use Manage\RestaurantBundle\Entity\KasboekSalarissen;
use Manage\RestaurantBundle\Entity\KasboekTurnover;
use Manage\RestaurantBundle\Form\KasboekType;
use Manage\RestaurantBundle\Form\KasboekContantenBedragType;
use Manage\RestaurantBundle\Form\KasboekContantenLosType;
use Manage\RestaurantBundle\Form\KasboekContantenRolType;
use Manage\RestaurantBundle\Form\KasboekContantenWaardeType;
use Manage\RestaurantBundle\Form\KasboekFloatsType;
use Manage\RestaurantBundle\Form\KasboekInType;
use Manage\RestaurantBundle\Form\KasboekKasType;
use Manage\RestaurantBundle\Form\KasboekOutType;
use Manage\RestaurantBundle\Form\KasboekSalarissenType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Manage\AdminBundle\Entity\RNotifierForm;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 * Kasboek controller.
 *
 * @Route("/kasboek")
 */
class KasboekController extends Controller {

    /**
     *
     * @Route("/new/", name="kasboek_new")
     * @Template()
     */
    public function newAction() {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN' && $user->getRole()!='ROLE_MANAGER' ){
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
        $entity_basic = new Kasboek();
        $entity_basic->setDated(new \DateTime());
        $entity_basic->setUpdated(new \DateTime());
        
        $em = $this->getDoctrine()->getManager();
        
        $los = new KasboekContanten();
        $em->persist($los);
        $rol = new KasboekContanten();
        $em->persist($rol);
        $waarde = new KasboekContanten();
        $em->persist($waarde);
        $bedrag = new KasboekContanten();
        $em->persist($bedrag);
        $floats = new KasboekFloats();
        $em->persist($floats);
        $salarissen = new KasboekSalarissen();
        $em->persist($salarissen);
        $kas = new KasboekKas();
        $em->persist($kas);
        $in = new KasboekIn();
        $em->persist($in);
        $out = new KasboekOut();
        $em->persist($out);

        //Crear las relaciones con el resto de las tablas de los formularios
        $entity_basic->setLos($los);
        $entity_basic->setRol($rol);
        $entity_basic->setWaarde($waarde);
        $entity_basic->setBedrag($bedrag);
        $entity_basic->setFloats($floats);
        $entity_basic->setSalarissen($salarissen);
        $entity_basic->setKas($kas);
        $entity_basic->setIn($in);
        $entity_basic->setOut($out);

        $mes_anterior = new \DateTime('today - 1 month');
        $str_mes_anterior = $mes_anterior->format('M-Y');
        $kasboek = $em->getRepository('RestaurantBundle:Kasboek')->findAll(array('dated'=>new \DateTime($str_mes_anterior)));
        if (count($kasboek) > 0){
            foreach ($kasboek as $item){
                $entity_basic->setBeginsaldo($item->getEindsaldo());
                break;
            }
        }
        $entity_basic = $this->updateCalculos($entity_basic);

        $em->persist($entity_basic);
        $em->flush();

        //Crear las relaciones con los datos dinámicos de turnover en kasboek
        for($i = 1; $i <= 31; $i ++){
            $turn = new KasboekTurnover();
            $turn->setKasboek($entity_basic);
            $turn->setDay($i);
            $em->persist($turn);
        }
        $em->flush();
        return $this->redirect($this->generateUrl('kasboek_edit', array('id' => $entity_basic->getId())));
    }

   
    /**
     * Lists all Kasboek entities.
     *
     * @Route("/date/{date}/", name="kasboek")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($date) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN' && $user->getRole()!='ROLE_MANAGER'){
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }

        $partes = explode('-', $date);
        $date = $partes[1].'-'.$partes[0];

        $em = $this->getDoctrine()->getManager();
        //$entities = $em->getRepository('RestaurantBundle:Kasboek')->findBy(array('dated'=>'> 2018-01-01', 'dated'=>'< 2018-01-31'), array('dated'=>'DESC'));
        $consulta = $em->createQuery('SELECT r FROM RestaurantBundle:Kasboek r WHERE r.dated >= \''.$date.'-01\' AND r.dated <= \''.$date.'-31\' ORDER BY r.dated DESC');
        $entities = $consulta->getResult();

        $consulta = $em->createQuery('SELECT r.form, count(r.id) AS cantidad FROM AdminBundle:RNotifierForm r JOIN r.notifier n WHERE n.form LIKE \'Kasboek\' GROUP BY r.form');
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
     * Finds and displays a Kasboek entity.
     *
     * @Route("/{id}/", name="kasboek_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN' && $user->getRole()!='ROLE_MANAGER'){
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
         $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $user=$this->get('security.token_storage')->getToken()->getUser();

        $entity_basic = $em->getRepository('RestaurantBundle:Kasboek')->find($id);
        if (!$entity_basic) {
            return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
        }
        
        $form_basic = $this->createForm(new KasboekType(), $entity_basic);
        $form_los = $this->createForm(new KasboekContantenLosType(), $entity_basic->getLos());
        $form_rol = $this->createForm(new KasboekContantenRolType(), $entity_basic->getRol());
        $form_waarde = $this->createForm(new KasboekContantenWaardeType(), $entity_basic->getWaarde());
        $form_bedrag = $this->createForm(new KasboekContantenBedragType(), $entity_basic->getBedrag());
        $form_floats = $this->createForm(new KasboekFloatsType(), $entity_basic->getFloats());
        $form_salarissen = $this->createForm(new KasboekSalarissenType(), $entity_basic->getSalarissen());
        $form_kas = $this->createForm(new KasboekKasType(), $entity_basic->getKas());
        $form_in = $this->createForm(new KasboekInType(), $entity_basic->getIn());
        $form_out = $this->createForm(new KasboekOutType(), $entity_basic->getOut());

        $response = new JsonResponse();

        $fecha = $entity_basic->getDated()->format('Y-m-d');
        $fecha_array = explode('-', $fecha);
        //$entity_basic = $this->updateCalculos($entity_basic);
        return $this->render('RestaurantBundle:Kasboek:edit.html.twig', array(
                    'entity_basic' => $entity_basic,
                    'form_basic' => $form_basic->createView(),
                    'form_los' => $form_los->createView(),
                    'form_rol' => $form_rol->createView(),
                    'form_waarde' => $form_waarde->createView(),
                    'form_bedrag' => $form_bedrag->createView(),
                    'form_floats' => $form_floats->createView(),
                    'form_salarissen' => $form_salarissen->createView(),
                    'form_kas' => $form_kas->createView(),
                    'form_in' => $form_in->createView(),
                    'form_out' => $form_out->createView(),
                    'turn1'  => $this->getFormsTurnover(1, $fecha_array[1], $fecha_array[0]),
                    'turn2'  => $this->getFormsTurnover(2, $fecha_array[1], $fecha_array[0]),
                    'turn3'  => $this->getFormsTurnover(3, $fecha_array[1], $fecha_array[0]),
                    'turn4'  => $this->getFormsTurnover(4, $fecha_array[1], $fecha_array[0]),
                    'turn5'  => $this->getFormsTurnover(5, $fecha_array[1], $fecha_array[0]),
                    'turnedit1'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 0),
                    'turnedit2'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 7),
                    'turnedit3'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 14),
                    'turnedit4'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 21),
                    'turnedit5'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 28),
                    'show'  => TRUE,
        ));
        
    }

    /**
     * Finds and displays a Kasboek entity.
     *
     * @Route("/{id}/delete/", name="kasboek_delete")
     * @Template()
     */
    public function deleteAction($id) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN' && $user->getRole()!='ROLE_MANAGER'){
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('RestaurantBundle:Kasboek')->find($id);
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if (!$entity) {
            return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
        }
        $now = new \DateTime('now');
        //echo  $entity_basic->getUpdated()->diff($now)->d ; die;
        //if (strtotime($olddate->format('d-m-Y')) > strtotime($entity_basic->getUpdated()->format('d-m-Y')) && $user->getRole() != 'ROLE_SUPERADMIN') {
        if (($entity->getUpdated()->diff($now)->d >= 2) && ($user->getRole() != 'ROLE_SUPERADMIN')) {
            $this->addFlash('error', 'Error! This form can not be removed.');
            return $this->redirect($this->generateUrl('kasboek', array('date'=>date('m-Y'))));
        }
        try {
            $em->remove($entity->getLos());
            $em->remove($entity->getRol());
            $em->remove($entity->getWaarde());
            $em->remove($entity->getBedrag());
            $em->remove($entity->getFloats());
            $em->remove($entity->getSalarissen());
            $em->remove($entity->getKas());
            $em->remove($entity->getIn());
            $em->remove($entity->getOut());
            $turn = $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity->getId()));
            foreach ($turn as $item){
                $em->remove($item);
            }
            $em->remove($entity);
            $em->flush();
        } catch (\Exception $ex) {
            return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
        }
        $this->addFlash('success', 'Success! The form has been removed.');
        return $this->redirect($this->generateUrl('kasboek', array('date'=>date('m-Y'))));
    }

    /**
     * @Route("/{id}/edit/", name="kasboek_edit")
     */
    public function editAction($id) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN' && $user->getRole()!='ROLE_MANAGER'){
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $user=$this->get('security.token_storage')->getToken()->getUser();

        $entity_basic = $em->getRepository('RestaurantBundle:Kasboek')->find($id);
        if (!$entity_basic) {
            return $this->render('AdminBundle:Exception:error404.html.twig', array('message' => 'Unable to find this Form.'));
        }
            //{% if 'today - 3 day' | date('d-m-Y') < entity.updated | date('d-m-Y') %}
        $olddate = new \DateTime('today - 3 day');
        $now = new \DateTime('now');
        //if (strtotime($olddate->format('d-m-Y')) > strtotime($entity_basic->getUpdated()->format('d-m-Y')) && ($user->getRole()!='ROLE_SUPERADMIN' || $user->getRole()!='ROLE_MANAGER')){
        if (($entity_basic->getUpdated()->diff($now)->d >= 2) && ($user->getRole() != 'ROLE_SUPERADMIN')) {
            $this->addFlash('error', 'Error! This form can not be modified.');
            return $this->redirect($this->generateUrl('kasboek', array('date'=>date('m-Y'))));
        }
        $entity_basic = $this->updateCalculos($entity_basic);

        $form_basic = $this->createForm(new KasboekType(), $entity_basic);
        $form_los = $this->createForm(new KasboekContantenLosType(), $entity_basic->getLos());
        $form_rol = $this->createForm(new KasboekContantenRolType(), $entity_basic->getRol());
        $form_waarde = $this->createForm(new KasboekContantenWaardeType(), $entity_basic->getWaarde());
        $form_bedrag = $this->createForm(new KasboekContantenBedragType(), $entity_basic->getBedrag());
        $form_floats = $this->createForm(new KasboekFloatsType(), $entity_basic->getFloats());
        $form_salarissen = $this->createForm(new KasboekSalarissenType(), $entity_basic->getSalarissen());
        $form_kas = $this->createForm(new KasboekKasType(), $entity_basic->getKas());
        $form_in = $this->createForm(new KasboekInType(), $entity_basic->getIn());
        $form_out = $this->createForm(new KasboekOutType(), $entity_basic->getOut());


        $response = new JsonResponse();
        if ($request->getMethod() == 'POST') {
            $form_basic->handleRequest($request);
            $form_los->handleRequest($request);
            $form_rol->handleRequest($request);
            $form_waarde->handleRequest($request);
            $form_bedrag->handleRequest($request);
            $form_floats->handleRequest($request);
            $form_salarissen->handleRequest($request);
            $form_kas->handleRequest($request);
            $form_in->handleRequest($request);
            $form_out->handleRequest($request);
            try {

                    $entity_basic->setUpdated(new \DateTime());
                   
                    /*$not = $em->getRepository('AdminBundle:RNotifierForm')->findOneBy(array('form'=>$entity_basic->getId()));
                    if (!is_null($entity_basic->getFinished()) ){
                        $entity_basic->setFinished(new \DateTime());
                        if (is_null($not)) $this->sendMail($id);
                    }*/
                    $em->persist($entity_basic);
                    $em->flush();

                    $response->setData('true');
                    return $response;

            } catch (\Exception $ex) {
                $response->setData($ex->getMessage());
                return $response;
            }
            return $response;
        } else {
            $fecha = $entity_basic->getDated()->format('Y-m-d');
            $fecha_array = explode('-', $fecha);

            return $this->render('RestaurantBundle:Kasboek:edit.html.twig', array(
                        'entity_basic' => $entity_basic,
                        'form_basic' => $form_basic->createView(),
                        'form_los' => $form_los->createView(),
                        'form_rol' => $form_rol->createView(),
                        'form_waarde' => $form_waarde->createView(),
                        'form_bedrag' => $form_bedrag->createView(),
                        'form_floats' => $form_floats->createView(),
                        'form_salarissen' => $form_salarissen->createView(),
                        'form_kas' => $form_kas->createView(),
                        'form_in' => $form_in->createView(),
                        'form_out' => $form_out->createView(),
                        'turn1'  => $this->getFormsTurnover(1, $fecha_array[1], $fecha_array[0]),
                        'turn2'  => $this->getFormsTurnover(2, $fecha_array[1], $fecha_array[0]),
                        'turn3'  => $this->getFormsTurnover(3, $fecha_array[1], $fecha_array[0]),
                        'turn4'  => $this->getFormsTurnover(4, $fecha_array[1], $fecha_array[0]),
                        'turn5'  => $this->getFormsTurnover(5, $fecha_array[1], $fecha_array[0]),
                        'turnedit1'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 0),
                        'turnedit2'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 7),
                        'turnedit3'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 14),
                        'turnedit4'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 21),
                        'turnedit5'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 28),
                        'show'  => FALSE,
            ));
        }
    }

    //Obtener los formularios turnover por fechas. Rangos de 7 días

    private function getFormsTurnover($rango, $mes, $anno){
        $em = $this->getDoctrine()->getManager();
        $entities = array();
        $control = false;
        switch ($rango){
            case 1:
                for ($i = 1; $i <= 7; $i++){
                    $result = $em->getRepository('RestaurantBundle:Turnover')->findOneBy(array(
                        'dated' => new \DateTime($anno.'-'.$mes.'-'.$i),
                    ));
                    if ($result != null)
                        $control = true;
                    $entities[] = $result;

                }
                break;
            case 2:
                for ($i = 8; $i <= 14; $i++){
                    $result = $em->getRepository('RestaurantBundle:Turnover')->findOneBy(array(
                        'dated' => new \DateTime($anno.'-'.$mes.'-'.$i),
                    ));
                    if ($result != null)
                        $control = true;
                    $entities[] = $result;
                }
                break;
            case 3:
                for ($i = 15; $i <= 21; $i++){
                    $result = $em->getRepository('RestaurantBundle:Turnover')->findOneBy(array(
                        'dated' => new \DateTime($anno.'-'.$mes.'-'.$i),
                    ));
                    if ($result != null)
                        $control = true;
                    $entities[] = $result;
                }
                break;
            case 4:
                for ($i = 22; $i <= 28; $i++){
                    $result = $em->getRepository('RestaurantBundle:Turnover')->findOneBy(array(
                        'dated' => new \DateTime($anno.'-'.$mes.'-'.$i),
                    ));
                    if ($result != null)
                        $control = true;
                    $entities[] = $result;
                }
                break;
            case 5:
                for ($i = 29; $i <= 31; $i++){
                    $result = $em->getRepository('RestaurantBundle:Turnover')->findOneBy(array(
                        'dated' => new \DateTime($anno.'-'.$mes.'-'.$i),
                    ));
                    if ($result != null)
                        $control = true;
                    $entities[] = $result;
                }
                break;
        }
        return $entities ;
    }

    //A partir de la entidad Kasboek se realizan todos los cálculos correspondientes
    private function updateCalculos($entity_kasboek){
        $str_date = $entity_kasboek->getDated()->format('Y-m');

        $em = $this->getDoctrine()->getManager();
        $turnovers = array();
        for ($i = 1; $i <= 31; $i++){
            $result = $em->getRepository('RestaurantBundle:Turnover')->findOneBy(array(
                'dated' => new \DateTime($str_date.'-'.$i),
            ));
            if ($result != null)
                $turnovers[] = $result;

        }
        //$turnovers = $em->getRepository('RestaurantBundle:Turnover')->findAll(array('dated'=>new \DateTime($str_date)));
        $calcs = array();
        $calcs['omzkitchen'] = 0;
        $calcs['omzlaag']  = 0;
        $calcs['omzhoog']  = 0;
        $calcs['omzspacerent']  = 0;
        $calcs['omzvouchers']  = 0;
        $calcs['omzentry']  = 0;
        $calcs['omzparking']  = 0;
        $calcs['omzothers']  = 0;

        $calcs['pinccv']= 0;
        $calcs['creditcards'] = 0;
        $calcs['rekening'] = 0;
        $calcs['vooverkoop'] = 0;
        $calcs['kadopagina'] = 0;
        $calcs['tickets'] = 0;
        $calcs['belevenissen'] = 0;
            foreach ($turnovers as $turnover) {
                $calcs['omzkitchen'] += (float)$turnover->getOmzet()->getOmzkitchen();
                $calcs['omzlaag'] += (float)$turnover->getOmzet()->getOmzlaag();
                $calcs['omzhoog'] += (float)$turnover->getOmzet()->getOmzhoog();
                $calcs['omzspacerent'] += (float)$turnover->getOmzet()->getOmzspacerent();
                $calcs['omzvouchers'] += (float)$turnover->getOmzet()->getOmzvouchersrek();
                $calcs['omzentry'] += (float)$turnover->getOmzet()->getOmzentry();
                $calcs['omzparking'] += (float)$turnover->getOmzet()->getOmzparking();
                $calcs['omzothers'] += (float)$turnover->getOmzet()->getOmzothers();

                $calcs['pinccv'] += (float)$turnover->getOntdebitcard();
                $calcs['creditcards'] += (float)$turnover->getOntcreditcard();
                $calcs['rekening'] += (float)$turnover->getOntrekening();
                $calcs['vooverkoop'] += (float)$turnover->getOntvooverkoop();
                $calcs['kadopagina'] += (float)$turnover->getOntkadopagina();
                $calcs['tickets'] += (float)$turnover->getOnttickets();
                $calcs['belevenissen'] += (float)$turnover->getOntbelevoucher();

            }

            $in = $entity_kasboek->getIn();
            $in->setOmzkitchen($calcs['omzkitchen']);
            $in->setOmzlaag($calcs['omzlaag']);
            $in->setOmzhoog($calcs['omzhoog']);
            $in->setOmzspacerent($calcs['omzspacerent']);
            $in->setOmzvoucher($calcs['omzvouchers']);
            $in->setOmzentry($calcs['omzentry']);
            $in->setOmzparking($calcs['omzparking']);
            $in->setOmzothers($calcs['omzothers']);


            $in->setOmzexkitchen($calcs['omzkitchen'] / 1.6);
            $in->setOmzexlaag($calcs['omzlaag'] / 1.6);
            $in->setOmzexhoog($calcs['omzhoog'] / 1.6);
            $in->setOmzexspacerent($calcs['omzspacerent'] / 1.6);
            $in->setOmzexvoucher($calcs['omzvouchers'] / 1.6);
            $in->setOmzexentry($calcs['omzentry'] / 1.6);
            $in->setOmzexparking($calcs['omzparking'] / 1.6);
            $in->setOmzexothers($calcs['omzothers'] / 1.6);

            $in->setOmzbtwkitchen($calcs['omzkitchen'] - $in->getOmzexkitchen());
            $in->setOmzbtwlaag($calcs['omzlaag'] - $in->getOmzexlaag());
            $in->setOmzbtwhoog($calcs['omzhoog'] - $in->getOmzexhoog());
            $in->setOmzbtwspacerent($calcs['omzspacerent'] - $in->getOmzexspacerent());
            $in->setOmzbtwvoucher($calcs['omzvouchers'] - $in->getOmzexvoucher());
            $in->setOmzbtwentry($calcs['omzentry'] - $in->getOmzexentry());
            $in->setOmzbtwparking($calcs['omzparking'] - $in->getOmzexparking());
            $in->setOmzbtwothers($calcs['omzothers'] - $in->getOmzexothers());

            $in->setOmztotalin($calcs['omzkitchen'] + $calcs['omzlaag'] + $calcs['omzhoog'] + $calcs['omzspacerent'] + $calcs['omzvouchers'] + $calcs['omzentry'] + $calcs['omzparking'] + $calcs['omzothers']);
            $in->setOmzextotalin($in->getOmzexkitchen() + $in->getOmzexlaag() + $in->getOmzexhoog() + $in->getOmzexspacerent() + $in->getOmzexvoucher() + $in->getOmzexentry() + $in->getOmzexparking() + $in->getOmzexothers());
            $in->setOmzbtwtotalin($in->getOmzbtwkitchen() + $in->getOmzbtwlaag() + $in->getOmzbtwhoog() + $in->getOmzbtwspacerent() + $in->getOmzbtwvoucher() + $in->getOmzbtwentry() + $in->getOmzbtwparking() + $in->getOmzbtwothers());
            $entity_kasboek->setIn($in);

            $out = $entity_kasboek->getOut();
            $out->setPinccv($calcs['pinccv']);
            $out->setCreditcards($calcs['creditcards']);
            $out->setRekening($calcs['rekening']);
            $out->setVoorverkoop($calcs['vooverkoop']);
            $out->setKadopagina($calcs['kadopagina']);
            $out->setTickets($calcs['tickets']);
            $out->setBelevenissen($calcs['belevenissen']);

            $out->setTotalout(
                $out->getPinccv() +
                $out->getCreditcards() +
                $out->getRekening() +
                $out->getVoorverkoop() +
                $out->getKadopagina() +
                $out->getTickets() +
                $out->getBelevenissen() +
                $out->getCash() +
                $out->getInkoopfood() +
                $out->getBedrijfskleding() +
                $out->getKleineinv() +
                $out->getWas() +
                $out->getBankkosten() +
                $out->getEntertainment() +
                $out->getDiversekosten());

            $out->setExinkoopfood($out->getInkoopfood() / 1.06);
            $out->setExbedrijfskleding($out->getBedrijfskleding() / 1.06);
            $out->setExkleineinv($out->getKleineinv() / 1.06);
            $out->setExwas($out->getWas() / 1.06);
            $out->setExentertainment($out->getEntertainment() / 1.06);
            $out->setExdiversekosten($out->getDiversekosten() / 1.06);

            $out->setBtwinkoopfood($out->getInkoopfood() - $out->getExinkoopfood());
            $out->setBtwbedrijfskleding($out->getBedrijfskleding() - $out->getExbedrijfskleding());
            $out->setBtwkleineinv($out->getKleineinv() - $out->getExkleineinv());
            $out->setBtwwas($out->getWas() - $out->getExwas());
            $out->setBtwentertainment($out->getEntertainment() - $out->getExentertainment());
            $out->setBtwdiversekosten($out->getDiversekosten() - $out->getExdiversekosten());

            $out->setExtotalout(
                $out->getExinkoopfood() +
                $out->getExbedrijfskleding() +
                $out->getExkleineinv() +
                $out->getExwas() +
                $out->getExentertainment() +
                $out->getExdiversekosten());

            $out->setBtwtotalout(
                $out->getBtwinkoopfood() +
                $out->getBtwbedrijfskleding() +
                $out->getBtwkleineinv() +
                $out->getBtwwas() +
                $out->getBtwentertainment() +
                $out->getBtwdiversekosten());
        $out->setSaldo(
            $entity_kasboek->getBeginsaldo() + $in->getOmztotalin() - $out->getTotalout()
        );

        $entity_kasboek->setEindsaldo($out->getSaldo());
        $entity_kasboek->setKasverschil($entity_kasboek->getTotalinkas()-$entity_kasboek->getEindsaldo());
        $entity_kasboek->setOut($out);

        $em->persist($entity_kasboek);
        $em->flush();

        return $entity_kasboek; 
    }

    private function sendMail($id){
        $em = $this->getDoctrine()->getManager();
        //Crear el notificador para este formulario.
        $notifier = $em->getRepository('AdminBundle:Notifier')->findOneBy(array('form'=>'Kasboek'));
        $entity_basic = $em->getRepository('RestaurantBundle:Kasboek')->findOneBy(array('id'=>$id));

        $fecha = $entity_basic->getDated()->format('Y-m-d');
        $fecha_array = explode('-', $fecha);

        $mails_array = explode(';',$notifier->getMails());
        $mail_customer = \Swift_Message::newInstance()
            ->setFrom('info@log.towerleisure.nl')
            ->setTo($mails_array)
            ->setSubject("Kasboek")
            ->setBody($this->renderView('RestaurantBundle:Kasboek:mail.html.twig', array(
                'entity_basic' => $entity_basic,
                'turn1'  => $this->getFormsTurnover(1, $fecha_array[1], $fecha_array[0]),
                'turn2'  => $this->getFormsTurnover(2, $fecha_array[1], $fecha_array[0]),
                'turn3'  => $this->getFormsTurnover(3, $fecha_array[1], $fecha_array[0]),
                'turn4'  => $this->getFormsTurnover(4, $fecha_array[1], $fecha_array[0]),
                'turn5'  => $this->getFormsTurnover(5, $fecha_array[1], $fecha_array[0]),
                'turnedit1'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 0),
                'turnedit2'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 7),
                'turnedit3'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 14),
                'turnedit4'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 21),
                'turnedit5'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 28),
)))
            ->setContentType("text/html");

        $this->get('mailer')->send($mail_customer);
        $mailer = new RNotifierForm();
        $mailer->setNotifier($notifier);
        $mailer->setForm($id);
        $mailer->setBody((string)$mail_customer->getBody());
        $mailer->setDate(new \DateTime('now'));
        $mailer->setSubject("Kasboek");
        $mailer->setTo($notifier->getMails());
        //Obtener el status del servidor de correo.
        $mailer->setStatus('Enviado');
        $em->persist($mailer);
        if (!is_null($notifier->getExternals())){
            $mails_array = explode(';',$notifier->getExternals());
            $mail_customer = \Swift_Message::newInstance()
                ->setFrom('info@log.towerleisure.nl')
                ->setTo($mails_array)
                ->setSubject("Kasboek")
                ->setBody($this->renderView('RestaurantBundle:Kasboek:mail.html.twig', array(
                    'entity_basic' => $entity_basic,
                    'turn1'  => $this->getFormsTurnover(1, $fecha_array[1], $fecha_array[0]),
                    'turn2'  => $this->getFormsTurnover(2, $fecha_array[1], $fecha_array[0]),
                    'turn3'  => $this->getFormsTurnover(3, $fecha_array[1], $fecha_array[0]),
                    'turn4'  => $this->getFormsTurnover(4, $fecha_array[1], $fecha_array[0]),
                    'turn5'  => $this->getFormsTurnover(5, $fecha_array[1], $fecha_array[0]),
                    'turnedit1'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 0),
                    'turnedit2'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 7),
                    'turnedit3'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 14),
                    'turnedit4'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 21),
                    'turnedit5'  => $em->getRepository('RestaurantBundle:KasboekTurnover')->findBy(array('kasboek'=>$entity_basic->getId()), array('day'=>'ASC'), 7, 28),
                )))

                ->setContentType("text/html");

            $this->get('mailer')->send($mail_customer);
            $mailer = new RNotifierForm();
            $mailer->setNotifier($notifier);
            $mailer->setForm($id);
            $mailer->setBody((string)$mail_customer->getBody());
            $mailer->setDate(new \DateTime('now'));
            $mailer->setSubject("Kasboek");
            $mailer->setTo($notifier->getExternals());
            //Obtener el status del servidor de correo.
            $mailer->setStatus('Enviado');
            $em->persist($mailer);
        }
        $em->flush();

    }

    /**
     * Displays a form to edit an existing KasboekParking entity.
     *
     * @Route("/{id}/mail/", name="kasboek_mail")
     */
    public function mailAction($id){
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN' && $user->getRole()!='ROLE_MANAGER'){
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
        try{
            $this->sendMail($id);
        } catch (\Exception $ex) {
            return $this->render('AdminBundle:Exception:exception.html.twig', array('message' => $ex));
        }
        $em = $this->getDoctrine()->getManager();
        $entity_basic = $em->getRepository('RestaurantBundle:Kasboek')->findOneBy(array('id'=>$id));
        //$date = $entity_basic->getDated()->format('m-Y');
        $this->addFlash('success', 'Success! The form has been sent.');
        return $this->redirect($this->generateUrl('kasboek', array('date'=>date('m-Y'))));
    }

    /**
     *
     * @Route("/kasboekchangedate/{id}/", name="kasboek_change_date")
     */
    public function kasboekchangedateAction($id, Request $request) {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN' && $user->getRole()!='ROLE_MANAGER'){
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }

        $response = new JsonResponse();

        try {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RestaurantBundle:Kasboek')->findOneBy(array('id' => $id));
            if ($entity->getFinished()== null){
                $entity->setDated(new \DateTime($request->get('newdate')));
                $entity->setUpdated(new \DateTime('now'));

                $em->persist($entity);
                $em->flush();
                $entity = $this->updateCalculos($entity, $request->get('date'));
                $response->setData('true');
            }

            else
                $response->setData('false');
            return $response;

        }catch (\Exception $ex) {
            $response->setData($ex);
            return $response;
        }

    }
    
    /**
     *
     * @Route("/kasboekturn/{id}/", name="kasboek_turnover")
     */
    public function kasboekTurnAction($id){
        $user=$this->get('security.token_storage')->getToken()->getUser();
        if ($user->getRole()!='ROLE_SUPERADMIN' && $user->getRole()!='ROLE_MANAGER'){
            return $this->render('AdminBundle:Exception:error403.html.twig', array('message' => 'You don\'t have permissions for this action'));
        }
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $data = $request->get('data');
        for ($i = 1; $i <= 31; $i ++){
            if (isset($data[$i])){
                $current_day = $em->getRepository('RestaurantBundle:KasboekTurnover')->findOneBy(array('kasboek'=>$id, 'day'=>$i));
                $current_day->setCash($data[$i]['cash']);
                $current_day->setInkoopfood($data[$i]['inkoopfood']);
                $current_day->setBedrijfskleding($data[$i]['bedrijfskleding']);
                $current_day->setKleineinv($data[$i]['kleineinv']);
                $current_day->setBankkosten($data[$i]['bankkosten']);
                $current_day->setWas($data[$i]['was']);
                $current_day->setEntertainment($data[$i]['entertainment']);
                $current_day->setDiversekosten($data[$i]['diversekosten']);
                $em->persist($current_day);
            }
        }
        $em->flush();
        die;
        //var_dump($request->get('data'));die;
        
    }


    /**
     * @Route("/{id}/finish/", name="kasboek_finish")
     * @Template()
     */
    public function finishAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity_basic = $em->getRepository('RestaurantBundle:Kasboek')->find($id);
        if ($entity_basic){
            $entity_basic->setFinished(new \DateTime());
            $not = $em->getRepository('AdminBundle:RNotifierForm')->findOneBy(array('form'=>$entity_basic->getId()));
            if (is_null($not)) $this->sendMail($id);
            $em->flush();
        }
        die;
    }
}
