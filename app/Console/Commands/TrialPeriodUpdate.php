<?php

namespace App\Console\Commands;

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
        $users = User::where('trial_periods', '0')->where('trial_period_end', '0')->get();

        if($users){
            foreach($users as $user){
                if($user->trial_ends_at <= now()){
                        User::where('id',$user->id)->update([
                            'trial_period_end' => '1'
                        ]);
                }
            }
        }
    }
}
