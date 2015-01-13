<?php

namespace HipchatClient\Action;

use HipchatClient\RoomApi;

class History extends AbstractAction {
    public function __construct($room, $client) {
        parent::__construct($room, $client);

        $roomApi = new RoomApi($client);
        $message = @$_POST['message'];
        if (! empty($message)) {
            $msg = new Message(array(
                'color'   => Message::COLOR_GRAY,
                'message' => $message,
                'message_format' => Message::FORMAT_TEXT
            ));
            $msg->setNotify(true);
            $sent = $roomApi->sendRoomNotification($room->getId(), $msg);
            header('Location: ' . $_SERVER['REQUEST_URI']);
            die;
        }

        $history = $roomApi->getRecentHistory($room->getId());
        $json = array_map(function($item){
            return $item->toJson();
        }, $history);

        echo json_encode($json);
    }
}

?>