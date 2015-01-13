<?php

namespace HipchatClient;

use GorkaLaucirica\HipchatAPIv2Client\Model\User as HipUser;
use HipchatClient\UserApi;

class User extends HipUser {

    const AWAY_LIMIT = 3000;

    protected $presence;

    public function parseJson($json)
    {
        $this->mentionName = $json['mention_name'];
        $this->id = $json['id'];
        $this->name = $json['name'];
        if (isset($json['links'])) {
            $this->links = $json['links'];
        }

        $this->presence = isset($json['presence']) ? (object)$json['presence'] : null;
        
        if(isset($json['xmpp_jid'])) {
            $this->xmppJid = $json['xmpp_jid'];
            $this->deleted = $json['is_deleted'];
            $this->lastActive = $json['last_active'];
            $this->title = $json['title'];
            $this->created = new \Datetime($json['created']);
            $this->groupAdmin = $json['is_group_admin'];
            $this->timezone = $json['timezone'];
            $this->guest = $json['is_guest'];
            $this->email = $json['email'];
            $this->photoUrl = $json['photo_url'];
        }
    }

    public function getPresence() {
        if ($this->presence->is_online) {
            $status = 'online';
            $diff = time() - (int)$this->lastActive;
            if ($diff > self::AWAY_LIMIT) {
                $status .= " away";
            }
        } else {
            $status = 'offline';
        }

        return $status;
    }

}