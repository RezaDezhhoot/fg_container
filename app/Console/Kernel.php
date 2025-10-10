<?php

namespace App\Console;

use App\Console\Commands\Deposit;
use App\Console\Commands\SyncCart;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\StartAppCommand::class,
        SyncCart::class,
        Deposit::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//         $schedule->command('inspire')->hourly();
        $schedule->command('command:sync-cart')->everyFiveMinutes()->runInBackground()->withoutOverlapping();
        $schedule->command('command:deposit')->everyFiveMinutes()->runInBackground()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
