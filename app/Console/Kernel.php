<?php

namespace App\Console;

use App\Console\Commands\ImageOfTheWeek;
use App\Console\Commands\TrialPeriodUpdate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

     protected $commands = [
        TrialPeriodUpdate::class,
        ImageOfTheWeek::class,
    ];
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('mins:trial-period-update')->everyMinute();
        $schedule->command('week:image-of-the-week')->weeklyOn(0, '5:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
