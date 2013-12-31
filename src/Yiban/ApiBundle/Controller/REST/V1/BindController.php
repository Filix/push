<?php

namespace Yiban\ApiBundle\Controller\REST\V1;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Yiban\ApiBundle\ApiStatus\ApiStatus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Yiban\ApiBundle\Controller\BaseController;
use Yiban\PushBundle\Entity\App;
use Yiban\PushBundle\Entity\Client;

/**
 * @Route("/bind")
 */
class BindController extends BaseController
{

    /**
     * @Route("", name="v1_bind")
     * @Method({"get"})
     * @ApiDoc(
     *  resource=true,
     *  description="绑定设备",
     *  section="V1: 绑定设备/Bind Client",
     *  filters={
     *      {"name"="access_token", "dataType"="string", "desc"="app access_token"},
     *      {"name"="uid", "dataType"="integer", "desc"="application user id"},
     *      {"name"="cid", "dataType"="string", "desc"="client id, get from push sdk"},
     *      {"name"="channel_id", "dataType"="string", "desc"="baidu sdk channel id"},
     *      {"name"="platform", "dataType"="string", "desc"="ANDROID|IOS"},
     *  }
     * )
     */
    public function bindAction()
    {
        #1086937199805221362
        #4607424723286984210
        #643674661335308582
        if (!$app = $this->getApp()) {
            return $this->error(ApiStatus::APP_NOT_EXSIT, 'app不存在');
        }
        $uid = $this->getParameter('uid');
        $cid = $this->getParameter('cid');
        $platform = strtoupper($this->getParameter('platform'));
        $where = array('cid' => $cid, 'app' => $app->getId());
        $channel_id = null;
        $validators = array('Client');
        if ($app->getType() == App::BAIDU_TYPE) {
            $channel_id = $this->getParameter('channel_id', null);
            $where['channel_id'] = $channel_id;
            $validators[] = 'baidu';
        }
        $client = $this->getClientRepository()->findOneBy($where);
        if (!$client) {
            $client = new Client();
        }
        $client->setApp($app);
        $client->setUid($uid);
        $client->setCid($cid);
        $client->setChannelId($channel_id);
        $client->setPlatform($platform);
        $client->setCreatedAt(new \DateTime());
        $validator = $this->get('validator');
        $errors = $validator->validate($client, $validators);
        if(count($errors)){
            return $this->error(ApiStatus::CLIENT_INVALID, $errors[0]->getMessage());
        }
        try{
            $dm = $this->getDoctrineManager();
            $dm->persist($client);
            $dm->flush();
        } catch (Exception $ex) {
            return $this->error(ApiStatus::CLIENT_SAVE_FAIL, '绑定失败');
        }
        return $this->success(null);
        
    }

}
