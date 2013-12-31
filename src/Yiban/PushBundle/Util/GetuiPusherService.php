<?php
namespace Yiban\PushBundle\Util;

use Yiban\PushBundle\Entity\Message;

class GetuiPusherService extends BasePusher
{
    public function __construct($entity_manager)
    {
        parent::__construct($entity_manager);
        $this->sdk = new IGeTui();
    }
    
    protected function pushNoticeRightNow(Message $message)
    {
        $app = $message->getApp();
        $this->sdk->init(array('app_key' => $app->getAppKey(), 'master_secret' => $app->getMasterSecret()));
        if($message->getClickAction() == Message::OPENURL_ACTION){
            $template = new \IGtLinkTemplate();
            $template->set_url($message->getClickUrl());
        }else if($message->getClickAction() == Message::OPENAPP_ACTION){
             $template =  new \IGtNotificationTemplate(); 
             $template->set_transmissionType(1); //收到消息是否立即启动应用：1 为立即启动,2 则广播等待客户端自启动
        }
        $template->set_appId($app->getAppId());//应用appid
	$template->set_appkey($app->getAppKey());//应用appkey
	
	// $template->set_transmissionContent("测试离线");//透传内容
	$template->set_title($message->getTitle());//通知栏标题
	$template->set_text($message->getMessage());//通知栏内容
	$template->set_isRing($message->getIsRing());//是否响铃
	$template->set_isVibrate($message->getIsShake());//是否震动
	$template->set_isClearable($message->getClearable());//通知栏是否可清除
        // iOS推送需要设置的pushInfo字段
        //$template ->set_pushInfo($actionLocKey,$badge,$message,$sound,$payload,$locKey,$locArgs,$launchImage);
        
        $m = new \IGtAppMessage();
        if($message->getOfflineTime() > 0){
            #离线保留时间
            $m->set_isOffline(true);
            $m->set_offlineExpireTime($message->getOfflineTime());
        }else{
            $m->set_isOffline(false);
        }
	$m->set_data($template);
 
	$m->set_appIdList(array($app->getAppId()));
	$m->set_phoneTypeList($message->getPlatform());
	$rep = $this->sdk->pushMessageToApp($m);
        if(isset($rep['result']) && $rep['result'] == 'ok'){
            return true;
        }
        return false;
    }

    protected function pushUserNoticeRightNow(Message $message)
    {
        
    }

}
