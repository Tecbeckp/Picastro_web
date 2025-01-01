<?php

namespace App\Jobs;

use App\Models\BlockToUser;
use App\Models\FollowerList;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPostNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $notificationService;
    public $userId;
    public $post;
    public function __construct($notificationService, $userId, $post)
    {
        $this->notificationService = $notificationService;
        $this->userId              = $userId;
        $this->post              = $post;
    }
    /**
     * Create a new job instance.
     */

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $authUserId = $this->userId;
        $followers = FollowerList::where('user_id', $authUserId)->pluck('follower_id')->toArray();
        $following = FollowerList::where('follower_id', $authUserId)->pluck('user_id')->toArray();
        $block_users = BlockToUser::whereAny(['user_id','block_user_id'], $authUserId)->get();

        $get_user_id = [];
        foreach($block_users as $user){
            $get_user_id[]=$user->user_id;
            $get_user_id[]=$user->block_user_id;
        }
        $unique_user = array_unique($get_user_id);
        $relatedUserIds = array_unique(array_merge($followers, $following));
        $unique_user_ids = array_diff($relatedUserIds, $unique_user);

        $users = User::select('id', 'fcm_token')->whereIn('id', $unique_user_ids)->get();
        $loginUser = User::where('id', $authUserId)->first();
        if ($users) {
            foreach ($users as $user) {
                $user_notification_setting = NotificationSetting::where('user_id', $user->id)->first();

                $notification = new Notification();
                $notification->user_id = $user->id;
                $notification->type    = 'Posts';
                $notification->post_image_id    = $this->post->id;
                $notification->notification = $loginUser->username . ' added a new post';
                $notification->save();
                $getnotification = Notification::select('id', 'user_id', 'type as title', 'notification as description', 'follower_id', 'post_image_id', 'trophy_id', 'is_read')->where('id', $notification->id)->first();
                if (!$user_notification_setting || ($user_notification_setting && $user_notification_setting->post == true)) {
                    $this->notificationService->sendNotification(
                        'Posts',
                        $loginUser->username . ' added a new post',
                        $user->fcm_token,
                        json_encode($getnotification)
                    );
                }
            }
        }
    }
}
