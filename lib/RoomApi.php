<?php

namespace HipchatClient;

use GorkaLaucirica\HipchatAPIv2Client\API\RoomAPI as HipRoomAPI;
use HipchatClient\Message;
use HipchatClient\Room;

class RoomApi extends HipRoomAPI {

    public function getRoom($id) {
        $response = $this->client->get("/v2/room/$id");
        $room = new Room($response, $this->client);
        return $room;
    }

    public function getRecentHistory($id, $params = array())
    {
        $response = $this->client->get(sprintf('/v2/room/%s/history/latest', $id), $params);

        $messages = array();
        foreach ($response['items'] as $response) {
            $messages[] = new Message($response);
        }
        return $messages;
    }

}