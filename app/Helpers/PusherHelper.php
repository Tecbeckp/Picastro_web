<?php

use Pusher\Pusher;

class PusherHelper
{
    private $app_key,
        $secret_key,
        $app_id,
        $cluster;

    private $pusher;

    public function __construct()
    {
        $this->app_id = env('PUSHER_APP_ID');
        $this->app_key = env('PUSHER_APP_KEY');
        $this->cluster = env('PUSHER_APP_CLUSTER');
        $this->secret_key = env('PUSHER_APP_SECRET');
        
        $this->pusher = new Pusher(
            $this->app_key,
            $this->secret_key,
            $this->app_id,
            array('cluster' => $this->cluster)
        );
    }


    public function pusherAuth($channel, $soecket, $user)
    {
        return $this->pusher->authorizeChannel($channel, $soecket, $user);
    }

    public function sendEvent($channel, $eventName, $data)
    {
        $this->pusher->trigger($channel, $eventName, $data);
    }


    public function sendSpecialistApprovedEvent($userId)
    {
        $this->sendEvent('private-user-' . $userId, 'logout_user', [
            'logout' => 'yes'
        ]);
    }


    public function sendPreOrderMessage($data)
    {
        $this->sendEvent(config('settings.pusher_preorder_chat_channel') . '-' . $data->invitation_id, 'pre_order_message', $data);
    }

    public function sendOrderDiscussionMessage($data)
    {
        $this->sendEvent(config('settings.pusher_main_chat_channel') . '-' . $data->order_id, 'main_message', $data);
    }


    public function sendCustomEvent($order_id, $event_type, $message, $to = "both")
    {
        $this->sendEvent(config('settings.pusher_main_chat_channel') . '-' . $order_id, 'custom_event', [
            'message' => $message,
            'event_type' => $event_type,
            "event_for" => $to
        ]);
    }

    public function reloadSupportChat($ticket_number)
    {
        $this->sendEvent('private-support-chat', 'reload_chat', [
            'ticket_number' => $ticket_number,
        ]);
    }

    public function reloadChatEvent($data, $redirect_out = false, $go_to_rating = false)
    {
//        \Illuminate\Support\Facades\Log::critical('reloadChatEvent start');
//        \Illuminate\Support\Facades\Log::critical('order ' . $data->order_id);
//        \Illuminate\Support\Facades\Log::critical('redirect_out ' . $redirect_out == true ? "yes" : "no");
//        \Illuminate\Support\Facades\Log::critical('go_to_rating ' . $go_to_rating == true ? "yes" : "no");
//        \Illuminate\Support\Facades\Log::critical('reloadChatEvent start');
        $this->sendEvent(config('settings.pusher_main_chat_channel') . '-' . $data->order_id, 'refresh_chat', [
            'redirect_out' => $redirect_out,
            'go_to_rating' => $go_to_rating
        ]);
    }


    public function reloadCustomerOrderList($user)
    {
        $this->reloadListEvent($user, 'refresh_customer_orders');
    }

    public function reloadSpecialistRequestsList($user)
    {
        $this->reloadListEvent($user, 'refresh_specialist_requests');
    }

    public function reloadSpecialistOrdersList($user)
    {
        $this->reloadListEvent($user, 'refresh_specialist_orders');
    }

    private function reloadListEvent($user, $event_name)
    {
        if (!empty($user)) {
            try {
                $this->sendEvent('private-list-activities-' . $user->id, $event_name, [
                    'reload' => true
                ]);
            } catch (Exception $exception) {

            }
        }
    }

}
