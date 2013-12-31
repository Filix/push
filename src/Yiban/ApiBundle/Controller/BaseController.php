<?php

namespace Yiban\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Yiban\ApiBundle\ApiStatus\ApiStatus;

class BaseController extends Controller
{
    public function getApp(){
        return $this->getAppRepository()->findOneBy(array('access_token' => $this->getParameter('access_token')));
    }
    
    public function getParameter($key, $default = null)
    {
        return $this->getRequest()->get($key, $default);
    }
    
    public function getService($id){
        return $this->container->get($id);
    }
    
    public function error($error_code, $code_message){
        return new JsonResponse(array('code' => $error_code, 'message' => $code_message));
    }
    
    public function success($data = null){
        return new JsonResponse(array('code' => ApiStatus::API_SUCCESS , 'message' => 'ok', 'data' => $data));
    }
    
    public function getAppRepository(){
        return $this->getDoctrine()->getRepository('YibanPushBundle:App');
    }
    
    public function getMessageRepository(){
        return $this->getDoctrine()->getRepository('YibanPushBundle:Message');
    }
    
    public function getClientRepository(){
        return $this->getDoctrine()->getRepository('YibanPushBundle:Client');
    }
    
    public function getDoctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }
}
