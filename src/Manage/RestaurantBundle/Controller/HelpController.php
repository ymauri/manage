<?php

namespace Manage\RestaurantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Manage\RestaurantBundle\Entity\Help;
use Manage\RestaurantBundle\Form\HelpType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Help controller.
 *
 * @Route("/help")
 */
class HelpController extends Controller {

    /**
     * Lists all Help entities.
     *
     * @Route("/", name="help")
     * @Security("is_granted('ROLE_FORMS_SETTINGS')")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
            $entities = $this->getDoctrine()->getRepository('RestaurantBundle:Help')->createQueryBuilder('h')
                ->select('h.form')
                ->distinct()
                ->getQuery();

            return array(
                'entities' => $entities->getResult(),
            );

    }

      /**
     * Lists all Help entities.
     * @Security("is_granted('ROLE_FORMS_SETTINGS')")
     * @Route("/edit/{form}", name="help_edit")
     */
    public function editAction(Request $request) {
            $form = $request->get('form');
            $em = $this->getDoctrine()->getManager();
            $result = $em->getRepository('RestaurantBundle:Help')->findBy(array('form'=>$form));
            $entities = array();
            if ($request->getMethod() == 'POST'){
                foreach ($result as $content){
                    $value = $request->get($content->getField());
                    $content->setContent($value);
                    $entities[$content->getField()] = array(
                        'label' => $content->getLabel(),
                        'content' => $content->getContent(),
                    );
                }
                $em->flush();
                $this->addFlash('success', 'Success! The help content has been updated.');
            }
            else{
                foreach ($result as $content){
                    $entities[$content->getField()] = array(
                        'label' => $content->getLabel(),
                        'content' => $content->getContent(),
                    );
                }
            }
            return $this->render('RestaurantBundle:Help:'.$form.'.html.twig', array(
                'form' => $entities,
            ));

    }




}
