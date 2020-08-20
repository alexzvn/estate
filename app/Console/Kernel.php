<?php

namespace App\Console;

use App\Console\Commands\ReverserPost;
use App\Console\Commands\SyncPermissionConfig;
use App\Repository\Setting;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SyncPermissionConfig::class,
        ReverserPost::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $setting = app(Setting::class);

        if ($setting->config('post.reverse', false)) {
            $schedule->command('post:reverser --item=3')
                ->everyTenMinutes()
                ->between('7:00', '22:00')
                ->appendOutputTo(storage_path('logs/schedule.log'));
        }
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
