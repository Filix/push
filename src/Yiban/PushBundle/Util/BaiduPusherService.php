<?php

namespace Yiban\PushBundle\Util;

use Yiban\PushBundle\Entity\Message;
use Yiban\PushBundle\Entity\App;

class BaiduPusherService extends BasePusher
{

    public function __construct($entity_manager)
    {
        parent::__construct($entity_manager);
        $this->sdk = new Baidu();
    }

    protected function pushNoticeRightNow(Message $message)
    {
        $app = $message->getApp();
        
        $this->sdk->init(array('api_key' => $app->getAppKey(), 'secret_key' => $app->getAppSecret()));
        $push_type = 3; //所有人
        $optional[Baidu::MESSAGE_TYPE] = 1; //指定消息类型为通知
        $optional[Baidu::MESSAGE_EXPIRES] = $message->getOfflineTime() * 3600;
        $m = array(
            'title' => $message->getTitle(),
            'description' => $message->getMessage(),
            'notification_builder_id' => 0,
            'notification_basic_style' => $this->getBasicStyle($message),
        );
        if($message->getClickAction() == Message::OPENURL_ACTION){
           $m = array_merge($m, array('open_type' => 1, 'url' => $message->getClickUrl()));
        }

        $platforms = $app->getPlatform();
        $result = false;
        if (in_array(App::IOS_PLATFORM, $platforms)) {
            //指定发到android设备
            $message_key = "ios_msg_key:" . $message->getId();
            $optional[Baidu::DEVICE_TYPE] = 4;
            $result = $this->sdk->pushMessage($push_type, $m, $message_key, $optional);
        }
        if (in_array(App::ANDROID_PLATFORM, $platforms)) {
            //指定发到android设备
            $message_key = "android_msg_key:" . $message->getId();
            $optional[Baidu::DEVICE_TYPE] = 3;
            $result = $this->sdk->pushMessage($push_type, $m, $message_key, $optional);
        }
        return $result ? true : false;
    }
    
    protected function pushUserNoticeRightNow(Message $message)
    {
        $app = $message->getApp();
        
        $this->sdk->init(array('api_key' => $app->getAppKey(), 'secret_key' => $app->getAppSecret()));
        $push_type = 1; //单个人
        $optional[Baidu::MESSAGE_TYPE] = 1; //指定消息类型为通知
        $optional[Baidu::MESSAGE_EXPIRES] = $message->getOfflineTime() * 3600;
        $m = array(
            'title' => $message->getTitle(),
            'description' => $message->getMessage(),
            'notification_builder_id' => 0,
            'notification_basic_style' => $this->getBasicStyle($message),
        );
        if($message->getClickAction() == Message::OPENURL_ACTION){
           $m = array_merge($m, array('open_type' => 1, 'url' => $message->getClickUrl()));
        }
        $result = false;
        $cs = $this->getClients($message);
        $message_key = "user_msg_key:" . $message->getId();
        foreach($cs as $key => $client){
            $optional[Baidu::USER_ID] = $client->getCId();
            $optional[Baidu::CHANNEL_ID] = $client->getChannelId();
            try{
                $r = $this->sdk->pushMessage($push_type, $m, $message_key.'_'.$key, $optional);
            } catch (Exception $ex) {
                $r = false;
            }
            
            if($r){
                $result = true;
            }
        }
        return $result;
    }
    
    protected function getBasicStyle(Message $message){
        if($message->getIsRing() && $message->getIsShake() && $message->getClearable()){
            return 0x07;
        }else if($message->getIsRing() && $message->getIsShake() && !$message->getClearable()){
            return 0x06;
        }else if($message->getIsRing() && !$message->getIsShake() && $message->getClearable()){
            return 0x05;
        }else if(!$message->getIsRing() && $message->getIsShake() && $message->getClearable()){
            return 0x03;
        }else if($message->getIsRing() && !$message->getIsShake() && !$message->getClearable()){
            return 0x04;
        }else if(!$message->getIsRing() && $message->getIsShake() && !$message->getClearable()){
            return 0x02;
        }else if(!$message->getIsRing() && !$message->getIsShake() && $message->getClearable()){
            return 0x01;
        } else{
            return 0x00;
        }
    }


}
