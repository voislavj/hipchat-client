<?php
namespace HipchatClient\Action;

use HipchatClient\Room;
use HipchatClient\Client;

abstract class AbstractAction {

    protected $room;

    public function __construct(Room $room, Client $client) {
        $this->room = $room;
    }
}