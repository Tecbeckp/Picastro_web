<?php

namespace App\Console\Commands;

use App\Models\GiveStar;
use App\Models\ImageOfWeek;
use App\Models\PostImage;
use App\Models\VoteImage;
use Illuminate\Console\Command;
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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startOfWeek = Carbon::now()->subWeek()->startOfWeek(); // Start of the current week (Sunday)
        $endOfWeek = Carbon::now()->subWeek()->endOfWeek(); // End of the current week (Saturday)

        // Get the post_image_ids for the current week
        $posts_id = PostImage::where('created_at', '>=', $startOfWeek)
            ->where('created_at', '<=', $endOfWeek)
            ->whereNotIn('user_id', ['41', '43'])
            ->pluck('id');

        // Get the vote count for each post_image_id
        $vote = VoteImage::select('post_image_id', DB::raw('count(id) as post_count'))
            ->whereIn('post_image_id', $posts_id)
            ->where('trophy_id', '1')
            ->groupBy('post_image_id')
            ->orderBy('post_count', 'desc')
            ->get();

        // Get the star count for each post_image_id
        $star = GiveStar::select('post_image_id', DB::raw('count(id) as post_count'))
            ->whereIn('post_image_id', $posts_id)
            ->groupBy('post_image_id')
            ->orderBy('post_count', 'desc')
            ->get();

        // Initialize an array to store the total counts
        $postCounts = [];

        // Combine the vote counts into the array
        foreach ($vote as $v) {
            $postCounts[$v->post_image_id]['votes'] = $v->post_count;
        }

        // Combine the star counts into the array
        foreach ($star as $s) {
            $postCounts[$s->post_image_id]['stars'] = $s->post_count;
        }

        // Calculate the total combined counts and store them
        foreach ($postCounts as $post_image_id => $counts) {
            $postCounts[$post_image_id]['total'] = ($counts['votes'] ?? 0) + ($counts['stars'] ?? 0);
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
                'total' => $counts['total'],
                'rank' => $currentRank,
            ];

            // Update the previous total to the current one
            $previousTotal = $counts['total'];

            // Increment the position (this is the next position in the list)
            $position++;
        }

        // Group posts by position (1st, 2nd, 3rd, etc.)


        foreach ($rankedPosts as $rankedPost) {
            ImageOfWeek::create([
                'post_id' =>  $rankedPost['post_image_id'],
                'vote' =>  $rankedPost['votes'],
                'star' =>  $rankedPost['stars'],
                'total' =>  $rankedPost['total'],
                'place' =>  $rankedPost['rank']
            ]);
        }
    }
}
