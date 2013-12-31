<?php

namespace Yiban\PushBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class IndexController extends Controller
{
    public function indexAction(){
        return new RedirectResponse($this->container->get('router')->generate('fos_user_security_login'));
    }
    
    public function profileAction(){
        return new RedirectResponse($this->container->get('router')->generate('fos_user_security_login'));
    }
}
