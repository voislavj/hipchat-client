<?php

namespace GorkaLaucirica\HipchatAPIv2Client;

use GorkaLaucirica\HipchatAPIv2Client\Auth\OAuth2;
use HipchatClient\Client;
use HipchatClient\RoomApi;
use HipchatClient\Message;
use \Exception;

session_start();

define("ROOM_NAME", "AdOps");

if (! isset($_SESSION['auth'])) {
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

$error = false;
$token = file_get_contents('token');

try {
    $auth  = new OAuth2($token);

    $client = new Client($auth);
    $roomApi = new RoomApi($client);
    $room = $roomApi->getRoom(ROOM_NAME);

    if ($room) {

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
    } else {
        $error = "Room not found.";
    }
}catch(Exception $e) {
    $error = $e->getMessage();
}
?>
<? if($error): ?><?= $error ?><? else: ?>
<!DOCTYPE html>
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
    <?= $room->getTopic() ?>
    <a class="logout" href="login?logout" title="Exit...">Logout</a>
</header>
<div class="users">
    <? foreach($room->getParticipants() as $user): ?>
        <p class="<?= $user->getPresence() ?>"><?= $user->getName() ?></p>
    <? endforeach ?>
</div>
<div class="history">
    <? foreach ($history as $item): ?>
    <div class="item">
        <div class="date"><span class="date"><?= $item->getDate() ?></span> <span class="time"><?= $item->getTime() ?></span></div>
        <strong class="from"><?= $item->getFrom() ?></strong>
        <div class="message"><?= $item->getMessage() ?></div>
    </div>
    <? endforeach ?>
</div>
<footer>
    <form action="./" method="post">
    <input type="text" name="message" placeholder="Message...">
    <input type="submit" value="Send">
    </form>
</footer>
<? endif ?>
</body>
</html>