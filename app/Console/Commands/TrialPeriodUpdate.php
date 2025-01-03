<?php

namespace App\Console\Commands;

use App\Helpers\PusherHelper;
use App\Models\SubscriptionHistory;
use App\Models\User;
use Illuminate\Console\Command;

class TrialPeriodUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mins:trial-period-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command for Update Trial Period';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pusherHelper = new PusherHelper();
        $users = User::where('trial_period_status', '2')->get();

        if ($users) {
            foreach ($users as $user) {
                if ($user->trial_ends_at <= date('Y-m-d H:i:s')) {
                    User::where('id', $user->id)->update([
                        'trial_period_status' => '0'
                    ]);
                    $subscription_history = SubscriptionHistory::where('user_id', $user->id)->where('subscription_id', 4)->first();

                    if ($subscription_history) {
                        User::where('id', $user->id)->update([
                            'subscription' => '1',
                            'subscription_id' => '4',
                        ]);
                    }
                    $pusherHelper->sendEvent('picastro-real-time-services', 'user_trial_period_end_' . $user->id, null);
                }
            }
        }
    }
}
