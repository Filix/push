<?php

namespace Yiban\PushBundle\Util;

use Yiban\PushBundle\Entity\Message;

abstract class BasePusher
{

    protected $entity_manager;
    /*
     * SdkInterface
     */
    protected $sdk;

    public function __construct($entity_manager)
    {
        $this->entity_manager = $entity_manager;
    }

    public function pushNotice(Message $message)
    {
        if ($message->getRightNow()) {
            if ($message->getUserId()) {
                $result = $this->pushUserNoticeRightNow($message);
            } else {
                $result = $this->pushNoticeRightNow($message);
            }
            if ($result) {
                $message->setResult(true);
                $message->setPublishedAt(new \DateTime);
                $this->entity_manager->persist($message);
                $this->entity_manager->flush();
            }
            return $result;
        }
        return $this->pushNoticeDelay($message);
    }

    /*
     * 定时发送
     */

    protected function pushNoticeDelay(Message $message)
    {
        return false;
    }

    protected function getClients(Message $message)
    {
        return $this->entity_manager->createQueryBuilder()->select('c')
                        ->from('YibanPushBundle:Client', 'c')
                        ->where('c.app = :app')
                        ->andWhere('c.uid = :uid')
                        ->getQuery()
                        ->setParameter('app', $message->getApp())
                        ->setParameter('uid', $message->getUserId())
                        ->getResult();
    }

    /*
     * @return boolean
     */

    abstract protected function pushNoticeRightNow(Message $message);
    /*
     * @return boolean
     */

    abstract protected function pushUserNoticeRightNow(Message $message);

//    abstract public function pushMessage(App $app, Message $message);
}
