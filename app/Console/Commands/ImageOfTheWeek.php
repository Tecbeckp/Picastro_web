<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\GiveStar;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\PostComment;
use App\Models\PostImage;
use App\Models\VoteImage;
use App\Models\WeekOfTheImage;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ImageOfTheWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'week:image-of-the-week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $startOfWeek = Carbon::now()->startOfWeek(); // Start of the current week (Sunday)
        $endOfWeek = Carbon::now()->endOfWeek(); // End of the current week (Saturday)

        // Get the post_image_ids for the current week
        $posts_id = PostImage::where('created_at', '>=', $startOfWeek)
            ->where('created_at', '<=', $endOfWeek)
            ->whereNotIn('user_id', ['41', '43','31'])
            ->pluck('id');

        // Get the vote count for each post_image_id
        $vote = VoteImage::select('post_image_id', DB::raw('count(id) as post_count'))
            ->whereIn('post_image_id', $posts_id)
            ->whereNotIn('user_id', ['41', '43'])
            ->where('trophy_id', '1')
            ->groupBy('post_image_id')
            ->orderBy('post_count', 'desc')
            ->get();

        // Get the star count for each post_image_id
        $star = GiveStar::select('post_image_id', DB::raw('count(id) as post_count'))
            ->whereIn('post_image_id', $posts_id)
            ->whereNotIn('user_id', ['41', '43'])
            ->groupBy('post_image_id')
            ->orderBy('post_count', 'desc')
            ->get();

        $comment = PostComment::select('post_image_id', DB::raw('count(id) as post_count'))
            ->whereIn('post_image_id', $posts_id)
            ->whereNotIn('user_id', ['41', '43'])
            ->groupBy('post_image_id')
            ->orderBy('post_count', 'desc')
            ->get();

        // Initialize an array to store the total counts
        $postCounts = [];

        // Combine the vote counts into the array
        foreach ($vote as $v) {
            $postCounts[$v->post_image_id]['votes'] = $v->post_count * 0.6;
        }

        // Combine the star counts into the array
        foreach ($star as $s) {
            $postCounts[$s->post_image_id]['stars'] = $s->post_count * 0.3;
        }

        // Combine the star counts into the array
        foreach ($comment as $c) {
            $postCounts[$c->post_image_id]['comments'] = $c->post_count * 0.1;
        }

        // Calculate the total combined counts and store them
        foreach ($postCounts as $post_image_id => $counts) {
            $postCounts[$post_image_id]['total'] = ($counts['votes'] ?? 0) + ($counts['stars'] ?? 0) + ($counts['comments'] ?? 0);
        }

        // Sort the posts by the total counts in descending order
        arsort($postCounts); // Sort by total count (descending)

        // Initialize ranked posts and assign positions
        $rankedPosts = [];
        $position = 1;
        $previousTotal = null;
        $currentRank = 1;

        foreach ($postCounts as $post_image_id => $counts) {
            // Stop the loop after the first 3 positions
            if ($position > 3) {
                break;
            }
            // If the total is the same as the previous one, it's a tie, so the rank stays the same
            if ($previousTotal !== null && $counts['total'] !== $previousTotal) {
                $currentRank = $position; // Update rank if total count is different
            }

            // Add the post to the ranked posts array
            $rankedPosts[] = [
                'post_image_id' => $post_image_id,
                'votes' => $counts['votes'] ?? 0,
                'stars' => $counts['stars'] ?? 0,
                'comments' => $counts['comments'] ?? 0,
                'total' => $counts['total'],
                'rank' => $currentRank,
            ];

            // Update the previous total to the current one
            $previousTotal = $counts['total'];

            // Increment the position (this is the next position in the list)
            $position++;
        }

        // Group posts by position (1st, 2nd, 3rd)

        WeekOfTheImage::truncate();

        foreach ($rankedPosts as $rankedPost) {
            WeekOfTheImage::create([
                'post_id' =>  $rankedPost['post_image_id'],
                'vote'    =>  $rankedPost['votes'],
                'star'    =>  $rankedPost['stars'],
                'comment' =>  $rankedPost['comments'],
                'total'   =>  $rankedPost['total'],
                'place'   =>  $rankedPost['rank']
            ]);

            if($rankedPost['rank'] == 1){
                $place = 'first';
            }elseif($rankedPost['rank'] == 2){
                $place = 'second';
            }else{
                $place = 'third';
            }
            $post = PostImage::with('user')->where('id', $rankedPost['post_image_id'])->first();
            if ($post) {
                $user_notification_setting = NotificationSetting::where('user_id', $post->user_id)->first();
                $notification = new Notification();
                $notification->user_id = $post->user_id;
                $notification->type    = 'Posts';
                $notification->post_image_id    = $rankedPost['post_image_id'];
                $notification->notification = 'Your incredible post has secured '.$place.' Place in the Image of the Week';
                $notification->save();
    
                $getnotification = Notification::select('id', 'user_id', 'type as title', 'notification as description', 'follower_id', 'post_image_id', 'trophy_id', 'is_read')->where('id', $notification->id)->first();
                if (!$user_notification_setting || ($user_notification_setting && $user_notification_setting->post == true)) {
                    $this->notificationService->sendNotification(
                        'Posts',
                        'Your incredible post has secured '.$place.' Place in the Image of the Week',
                        $post->user->fcm_token,
                        json_encode($getnotification)
                    );
                }
            }
        }
    }
}
