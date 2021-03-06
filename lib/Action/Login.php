<?php

namespace HipchatClient\Action;

class Login extends AbstractAction {

    public function __construct($room, $client) {
        parent::__construct($room, $client);

        if (isset($_GET['logout'])) {
            $this->logout();
        } else if(isset($_POST['login'])) {
            $this->login($_POST['login']);
        } else {
            $this->loginScreen();
        }
    }

    protected function loginScreen($message=null) {
        ?><!DOCTYPE html>
        <html>
        <head>
            <title>Authentication required</title>
            <meta charset="utf-8">
            <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
            <link rel="stylesheet" href="css/style.css">
            <style type="text/css">
            html {
                display:table;
                width:100%;
            }
            body {
                display:table-row;
            }
            h1 {
                margin-bottom:20px;
            }
            form {
                display:table-cell;
                vertical-align: middle;
                text-align: center;
            }
            label {
                display:block;
                cursor:pointer;
            }
            input[type=text], input[type=password] {
                display: block;
                margin:5px auto 10px;
                text-align: center;
                padding:4px;
            }
            input[type=submit] {
                padding:4px 10px;
                cursor:pointer;
                text-transform: uppercase;
            }
            .error {
                margin-top:20px;
            }
            .error span{
                padding:5px;
                margin:10px 0;
                background:red;
                color:white;
                width:auto;
            }
            </style>
        </head>

        <body>
            <form action="login" method="post">
                <h1>Login</h1>
                <label>Username:<input type="text" name="login[username]"></label>
                <label>Password:<input type="password" name="login[password]"></label>
                <input type="submit" value="login">
                <? if(!empty($message)): ?><p class="error"><span><?= $message ?></span></p><? endif ?>
            </form>
        </body>
        </html>
        <?php
    }

    protected function login($data) {
        $message = '';
        if (! empty($data)) {
            extract($data);
            if ($username!='admin' || md5($password)!='7fa68c7e19c02e9b0e6fd10cdf08b578') {
                $message = 'Login failed.';
            } else {
                $_SESSION['auth'] = true;
                header('Location: index');
                die;
            }
        }

        $this->loginScreen($message);
    }

    protected function logout() {
        unset($_SESSION['auth']);
        header('Location: index');
        die;
    }
}