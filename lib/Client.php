<?php

namespace HipchatClient;

use GorkaLaucirica\HipchatAPIv2Client\Client as HipClient;
use GorkaLaucirica\HipchatAPIv2Client\Auth\AuthInterface;
use Buzz\Client\Curl;
use Buzz\Browser;

class Client extends HipClient {

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
        
        $c = new Curl();
        $c->setVerifyPeer(false);
        $this->browser = new Browser($c);
    }
}