<?php

namespace HipchatClient;

use GorkaLaucirica\HipchatAPIv2Client\Model\Message as HipMessage;

class Message extends HipMessage {
    

    public function getFrom()
    {
        return $this->from;
    }

    public function getDate($format="Y-m-d H:i:s")
    {
        $time = strtotime($this->date);
        return date($format, $time);
    }
}