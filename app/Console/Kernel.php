<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $now = Carbon::now()->format('YmdHis');

        $schedule->command('ClosedTicketCron')
            ->timezone('Asia/Jakarta')
            // ->dailyAt('23:59')
            ->everyMinute()
            ->sendOutputTo("storage/logs/LogClosedTicketCron_" . $now . ".txt");
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
