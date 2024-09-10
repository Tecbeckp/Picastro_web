<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;

class NotificationService
{
    protected $googleAccessTokenService;

    public function __construct(GoogleAccessTokenService $googleAccessTokenService)
    {
        $this->googleAccessTokenService = $googleAccessTokenService;
    }

    public function sendNotification($title, $body, $targetToken,$notification_detail = null, $chat_notification_detail= null)
    {
        $accessToken = $this->googleAccessTokenService->getAccessToken();

        $client = new Client();
        try{
            $response = $client->post('https://fcm.googleapis.com/v1/projects/picastro-app-3d461/messages:send', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'message' => [
                        'token' => $targetToken,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        'data' => [
                            'notification_detail'      => $notification_detail,
                            'chat_notification_detail' => $chat_notification_detail
                        ]
                    ],
                ],
            ]);
    
            return $response->getBody()->getContents();
        } catch (\GuzzleHttp\Exception\ClientException $e) {
                $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);
                if (isset($responseBody['error']['status'])) {
                    $errorStatus = $responseBody['error']['status'];
    
                    if ($errorStatus === 'UNREGISTERED' || $errorStatus === 'INVALID_ARGUMENT' || $errorStatus === 'NOT_FOUND') {
                        $this->handleInvalidToken($targetToken);
                        return 'The FCM token is invalid or expired.';
                    }
                }
            // throw $e;
        }
    }

    private function handleInvalidToken($targetToken)
    {
        return  User::where('fcm_token', $targetToken)->update(['fcm_token' => null]);
    }
   
}
