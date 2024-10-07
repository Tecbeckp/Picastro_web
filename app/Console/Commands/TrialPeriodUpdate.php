<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Helpers\PusherHelper;
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
    protected $pusherHelper;

    public function __construct(PusherHelper $pusherHelper)
    {
        $this->pusherHelper = $pusherHelper;
    }
    public function handle()
    {
        $users = User::where('trial_period_status', '2')->get();

        if($users){
            foreach($users as $user){
                if($user->trial_ends_at <= date('Y-m-d H:i:s')){
                        User::where('id',$user->id)->update([
                            'trial_period_status' => '0'
                        ]);
            $this->pusherHelper->sendEvent('picastro-real-time-services','user_trial_period_end_'.$user->id , null);

                }
            }
        }
    }
}
