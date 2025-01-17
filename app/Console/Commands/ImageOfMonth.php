<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\PostImage;
use App\Models\VoteImage;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImageOfMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:image-of-month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run monthly tasks on the 28th of every month';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentMonth = Carbon::now()->format('m-Y');
        $data = VoteImage::select('post_image_id', 'month', DB::raw('count(id) as post_count'))
            ->whereHas('postImage', function ($q) {
                $q->whereNull('deleted_at')->whereNotIn('user_id', ['41', '43']);
            })
            ->where('trophy_id', '1')
            ->whereNotIn('user_id', ['41', '43'])
            ->where('month', $currentMonth)
            ->groupBy('month', 'post_image_id')
            ->orderBy('post_count', 'desc')
            ->get()
            ->groupBy('month')
            ->map(function ($groupedByMonth) {
                return [
                    'post_image_id' => $groupedByMonth->first()->post_image_id,
                    'month' => $groupedByMonth->first()->month,
                    'post_count' => $groupedByMonth->first()->post_count
                ];
            });

        $res  = $data[$currentMonth];

        $update_Res = VoteImage::where('post_image_id', $res['post_image_id'])->where('month', $res['month'])->first();
        $update_Res->update([
            'IOT' => '1',
            'IOT_date' => now()
        ]);
        $post = PostImage::with('user')->where('id', $res['post_image_id'])->first();
        if ($post) {
            $user_notification_setting = NotificationSetting::where('user_id', $post->user_id)->first();
            $notification = new Notification();
            $notification->user_id = $post->user_id;
            $notification->type    = 'Posts';
            $notification->post_image_id    = $res['post_image_id'];
            $notification->notification = 'Your stunning post has been crowned Image of the Month';
            $notification->save();

            $getnotification = Notification::select('id', 'user_id', 'type as title', 'notification as description', 'follower_id', 'post_image_id', 'trophy_id', 'is_read')->where('id', $notification->id)->first();
            if (!$user_notification_setting || ($user_notification_setting && $user_notification_setting->post == true)) {
                $this->notificationService->sendNotification(
                    'Posts',
                    'Your stunning post has been crowned Image of the Month',
                    $post->user->fcm_token,
                    json_encode($getnotification)
                );
            }
        }
    }
}
