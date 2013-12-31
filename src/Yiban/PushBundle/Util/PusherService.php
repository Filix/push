<?php
namespace Yiban\PushBundle\Util;
use Yiban\PushBundle\Entity\Message;
use Yiban\PushBundle\Entity\App;
use Yiban\PushBundle\Util\BasePusher;

class PusherService
{
    protected $container;
    protected $pusher;

    public function __construct($container)
    {
        $this->container = $container;
    }
    
    public function pushNotice(Message $message){
        $app = $message->getApp();
        switch ($app->getType()){
            case App::BAIDU_TYPE:
                $this->pusher = $this->container->get('yiban.pusher.baidu');
                break;
            case App::GETUI_TYPE:
                $this->pusher = $this->container->get('yiban.pusher.getui');
                break;
            case App::JPUSH_TYPE:
                $this->pusher = $this->container->get('yiban.pusher.jpush');
                break;
        }
        if(!$this->pusher instanceof BasePusher){
            return false;
        }
        return $this->pusher->pushNotice($message);
    }
}
