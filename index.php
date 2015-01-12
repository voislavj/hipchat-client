<?php

namespace GorkaLaucirica\HipchatAPIv2Client;

use GorkaLaucirica\HipchatAPIv2Client\Auth\OAuth2;
use GorkaLaucirica\HipchatAPIv2Client\Client;
use GorkaLaucirica\HipchatAPIv2Client\API\RoomAPI;

spl_autoload_register(function($class) {
    
    if (preg_match('/^Buzz/', $class)) {
        $path = "lib/Buzz/lib/{$class}.php";
        require_once $path;
    } else {
        $path = preg_replace('/'.str_replace("\\", "\\\\", __NAMESPACE__)."\\\\/", '', $class) . ".php";
        require_once $path;
    }
});

$token = file_get_contents('token');
$auth  = new OAuth2($token);

$client = new Client($auth);

$roomApi = new RoomAPI($client);
$rooms = $roomApi->getRooms(array('max-results' => 50));
foreach($rooms as $room) {
    echo "{$room->getName()}<br>";
}

?>