<?php

namespace Yiban\PushBundle\Util;

use Yiban\PushBundle\Entity\Message;
use Yiban\PushBundle\Entity\App;

class JpushPusherService extends BasePusher
{

    public function __construct($entity_manager)
    {
        parent::__construct($entity_manager);
//        $this->sdk = new Baidu();
    }

    protected function pushNoticeRightNow(Message $message)
    {
        return false;
    }

}
