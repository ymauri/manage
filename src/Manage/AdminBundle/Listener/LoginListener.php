<?php
namespace Manage\AdminBundle\Listener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Routing\Router;

class LoginListener {
    private $context, $router, $loged_user = null;
    public function __construct(SecurityContext $context, Router $router) {
        $this->context=$context;
        $this->router =$router;
    }
    
    public function onSecurityInteractiveLogin (InteractiveLoginEvent $event){
        $token = $event->getAuthenticationToken();
        $this->loged_user = $token->getUser();
        //$user->setUltimaConexion(new \DateTime());
    }
    
    public function onKernelResponse(FilterResponseEvent $event){        
//        $portada = $this->router->generate('admin_homepage', array(
//            'user' => $this->loged_user
//        ));
//        $event->setResponse(new RedirectResponse($portada));
//        $event->stopPropagation();
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

