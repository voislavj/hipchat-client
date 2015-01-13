<?php

namespace HipchatClient\Action;

class Users extends AbstractAction {

    public function __construct($room, $client) {
        parent::__construct($room, $client);
        
        $json = array();
        foreach($room->getParticipants() as $user) {
            $u = $user->toJson();
            $u['id']       = $user->getId();
            $u['presence'] = $user->getPresence();
            $json[] = $u;
        }

        echo json_encode($json);
    }
}