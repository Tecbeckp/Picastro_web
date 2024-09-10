<?php

namespace App\Services;

use Google_Client;

class GoogleAccessTokenService
{
    protected $keyFilePath;

    public function __construct()
    {
        $this->keyFilePath = base_path('picastro-app-3d461-7afa5800f078.json');
    }

    public function getAccessToken()
    {
        $client = new Google_Client();
        $client->setAuthConfig($this->keyFilePath);
        $client->addScope('https://www.googleapis.com/auth/cloud-platform');

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithAssertion();
        }

        return $client->getAccessToken()['access_token'];
    }
}
