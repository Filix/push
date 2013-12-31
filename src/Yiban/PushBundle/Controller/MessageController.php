<?php

namespace Yiban\PushBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MessageController extends Controller
{

    public function createAction()
    {
        return new RedirectResponse($this->get('router')->generate('admin_yiban_push_app_list'));
    }
    
    public function editAction($id = null)
    {
        return new RedirectResponse($this->get('router')->generate('admin_yiban_push_message_list'));
    }

}
