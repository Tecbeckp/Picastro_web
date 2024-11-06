<?php

namespace App\Jobs;

use App\Models\FollowerList;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
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

        $relatedUserIds = array_unique(array_merge($followers, $following));

        $users = User::select('id', 'fcm_token')->whereIn('id', $relatedUserIds)->get();
        $loginUser = User::where('id',$authUserId)->first();
        if ($users) {
            foreach ($users as $user) {
                $notification = new Notification();
                $notification->user_id = $user->id;
                $notification->type    = 'New Post';
                $notification->post_image_id    = $this->post->id;
                $notification->notification = $loginUser->username .' added a new post';
                $notification->save();
                $getnotification = Notification::select('id', 'user_id', 'type as title', 'notification as description', 'follower_id', 'post_image_id', 'trophy_id', 'is_read')->where('id', $notification->id)->first();

                $this->notificationService->sendNotification(
                    'New Post',
                    $loginUser->username . ' added a new post',
                    $user->fcm_token,
                    json_encode($getnotification)
                );
            }
        }
    }
}
