<?php

namespace GorkaLaucirica\HipchatAPIv2Client;

use GorkaLaucirica\HipchatAPIv2Client\Auth\OAuth2;
use HipchatClient\Client;
use HipchatClient\RoomApi;
use HipchatClient\Message;
use \Exception;

session_start();

define("ROOM_NAME", "AdOps");

$action = @$_GET['a'];

if (! isset($_SESSION['auth']) && $action!='login') {
    header('Location: login');
    die;
}

spl_autoload_register(function($class) {
    if (preg_match('/^Buzz/', $class)) {
        $path = "lib/Buzz/lib/{$class}.php";
    } else if (preg_match('/^HipchatClient/', $class)) {
        $path = "lib/" . preg_replace('/^HipchatClient\\\\/', '', $class) . ".php";
    } else {
        $path = "lib/Hipchat/" . preg_replace('/'.str_replace("\\", "\\\\", __NAMESPACE__)."\\\\/", '', $class) . ".php";
    }

    $path = str_replace("\\", "/", $path);
    require_once $path;
});

$token = file_get_contents('token');
$auth  = new OAuth2($token);
$client = new Client($auth);
$roomApi = new RoomApi($client);
$room = $roomApi->getRoom(ROOM_NAME);

$className = ucfirst($action);
if ($className && $className!='Index') {
    $class = "HipchatClient\\Action\\{$className}";
    new $class($room, $client);
    die;
}

?><!DOCTYPE html>
<html>
<head>
    <title><?= $room->getName() ?>: <?= $room->getTopic() ?></title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/mobile.css">
    
    <script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
</head>

<body>
<header>
    <h1>
        <?= $room->getName() ?>
        <span class="topic"><?= $room->getTopic(true) ?></span>
    </h1>
    <a class="logout" href="login?logout" title="Exit...">Logout</a>
</header>
<ul class="users">
    
</ul>
<div class="history">
    
</div>
<footer>
    <form action="./" method="post">
    <input type="text" name="message" placeholder="Message...">
    <input type="submit" value="Send">
    </form>
</footer>
</body>
</html>