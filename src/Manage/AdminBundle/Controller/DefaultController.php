<?php

namespace Manage\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller {

    /**
     * Website main page.
     *
     * @Route("/home", name="home")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        return $this->render('AdminBundle:Default:index.html.twig');
    }

    /**
     * Website login.
     *
     * @Route("/login", name="login")
     */
    public function loginAction() {
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->render('AdminBundle:Default:login.html.twig', array(
                    'email' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
        ));
    }

    /**
     * Website login.
     *
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction() {
        // The security layer will intercept this request
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction() {
        //close session
    }

}
