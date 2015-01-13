<?php

namespace HipchatClient;

use GorkaLaucirica\HipchatAPIv2Client\API\UserAPI as HipUserApi;
use HipchatClient\User;

class UserApi extends HipUserApi {

    public function getUser($id)
    {
        if (isset($_SESSION['User'][$id])) {
            $response = $_SESSION['User'][$id];
        } else {
            $response = $this->client->get("/v2/user/$id");
            $_SESSION['User'][$id] = $response;
        }

        return new User($response);
    }

}