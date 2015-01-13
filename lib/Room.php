<?php

namespace HipchatClient;

use GorkaLaucirica\HipchatAPIv2Client\Model\Room as HipRoom;
use HipchatClient\User;
use HipchatClient\UserApi;

class Room extends HipRoom {
    protected $client;
    protected $apiUser;
    
    public function __construct($json=null, $client=null) {
        $this->client = $client;
        $this->apiUser = new UserAPI($client);
        
        parent::__construct($json);
    }

    public function parseJson($json)
    {
        $this->id = $json['id'];
        $this->name = $json['name'];
        $this->links = $json['links'];

        if (isset($json['xmpp_jid'])) {
            $this->xmppJid = $json['xmpp_jid'];
            //Statistics need to be implemented
            $this->created = new \DateTime($json['created']);
            $this->archived = $json['is_archived'];
            $this->privacy = $json['privacy'];
            $this->guestAccessible = $json['is_guest_accessible'];
            $this->topic = $json['topic'];
            $this->participants = array();
            foreach ($json['participants'] as $participant) {
                if (! $participant['presence']) {
                    $this->participants[] = $this->apiUser->getUser($participant['id']);
                } else {
                    $this->participants[] = new User($participant);
                }
            }
            $this->owner = new User($json['owner']);
            $this->guestAccessUrl = $json['guest_access_url'];
        }
    }
}

?>