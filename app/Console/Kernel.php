<?php

namespace App\Console;

use App\Helper\Settings;
use Http;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $data = Settings::getScheduleSettings();
            if ($data['auto']){
                Http::timeout(5)->get('http://localhost:5000/retrain');
            }
        })->monthlyOn(Settings::getScheduleSettings()['date'], '00:00');
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
