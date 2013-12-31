<?php
namespace Yiban\ApiBundle\Controller\REST\V1;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Yiban\ApiBundle\ApiStatus\ApiStatus;
use Yiban\PushBundle\Entity\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Yiban\ApiBundle\Controller\BaseController;

/**
 * @Route("/push")
 */
class PushController extends BaseController
{
    /**
     * @Route("/notice_to_app", name="v1_push_notice_to_app")
     * @Method({"get"})
     * @ApiDoc(
     *  resource=true,
     *  description="向app推送通知",
     *  section="V1: 推送通知/Push Notice",
     *  filters={
     *      {"name"="access_token", "dataType"="string", "desc"="app access_token"},
     *      {"name"="title", "dataType"="string", "desc"="notice title"},
     *      {"name"="message", "dataType"="string", "desc"="notice content"},
     *      {"name"="platform", "dataType"="string", "desc"="platform", "pattern"="ANDROID|IOS"},
     *      {"name"="is_ring", "dataType"="boolean", "desc"="is ring when notice arrives"},
     *      {"name"="is_shake", "dataType"="boolean", "desc"="is shake when notice arrives"},
     *      {"name"="clearable", "dataType"="boolean", "desc"="is clearable when notice arrives"},
     *      {"name"="click_action", "dataType"="string", "desc"="action when click the notice", "pattern"="OPENAPP|OPENURL"},
     *      {"name"="clike_url", "dataType"="string", "desc"="url redirect to"},
     *      {"name"="offline_time", "dataType"="string", "desc"="offline time"},
     *  }
     * )
     */
    public function pushNoticeToAppAction()
    {
        if(!$app = $this->getApp()){
            return $this->error(ApiStatus::APP_NOT_EXSIT, 'app不存在');
        }
        $message = new Message();
        $message->setType(Message::NOTICE_TYPE);
        $message->setApp($app);
        $message->setTitle($this->getRequest()->get('title'));
        $message->setMessage($this->getRequest()->get('message'));
        $platforms = explode('|', strtoupper($this->getRequest()->get('platform')));
        $platforms = array_filter($platforms);
        $message->setPlatform($platforms);
        $message->setIsRing($this->getRequest()->get('is_ring', true));
        $message->setIsShake($this->getRequest()->get('is_shake', false));
        $message->setClearable($this->getRequest()->get('clearable', true));
        $message->setClickAction(strtoupper($this->getRequest()->get('click_action', Message::OPENAPP_ACTION)));
        $message->setClickUrl($this->getRequest()->get('click_url'));
        $message->setOfflineTime($this->getRequest()->get('offline_time', 0));
        $validator = $this->getService('validator');
        $errors = $validator->validate($message);
        if(count($errors)){
            return $this->error(ApiStatus::MESSAGE_INVALID, $errors[0]->getMessage());
        }
        $s = $this->getService('yiban.pusher');
        $result = $s->pushNotice($message);
        if($result){
            return $this->success(null);
        }else{
             return $this->error(ApiStatus::MESSAGE_PUSH_FAIL, '推送失败');
        }
    }
    
    /**
     * @Route("/notice_to_user", name="v1_push_notice_to_user")
     * @Method({"POST"})
     * @ApiDoc(
     *  resource=true,
     *  description="向单个用户推送消息",
     *  section="V1: 推送通知/Push Notice",
     *  filters={
     *      {"name"="access_token", "dataType"="string", "desc"="app access_token"},
     *      {"name"="title", "dataType"="string", "desc"="notice title"},
     *      {"name"="message", "dataType"="string", "desc"="notice content"},
     *      {"name"="platform", "dataType"="string", "desc"="platform", "pattern"="ANDROID|IOS"},
     *      {"name"="is_ring", "dataType"="boolean", "desc"="is ring when notice arrives"},
     *      {"name"="is_shake", "dataType"="boolean", "desc"="is shake when notice arrives"},
     *      {"name"="clearable", "dataType"="boolean", "desc"="is clearable when notice arrives"},
     *      {"name"="click_action", "dataType"="string", "desc"="action when click the notice", "pattern"="OPENAPP|OPENURL"},
     *      {"name"="clike_url", "dataType"="string", "desc"="url redirect to"},
     *      {"name"="offline_time", "dataType"="string", "desc"="offline time"},
     *  }
     * )
     */
    public function pushNoticeToUserAction()
    {
        EXIT;
    }
}
