<?php

namespace App\Console;

use App\Console\Commands\ReverserPost;
use App\Console\Commands\SyncPermissionConfig;
use App\Models\Post;
use App\Models\TrackingPost;
use App\Setting;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Collection;

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
        $setting = Setting::load();

        if ($setting->compareStrict('post.reverse', true)) {
            $schedule->command('post:reverser --item=3')
                ->everyTenMinutes()
                ->between('7:00', '22:00')
                ->appendOutputTo(storage_path('logs/schedule.log'));
        }

        // Index all recent post to elastic
        $indexer = function () {
            $thePast = now()->subMinutes(5);

            Post::where('publish_at', '>', $thePast)
                ->orWhere('created_at', '>', $thePast)
                ->get()
                ->searchable();
        };

        $schedule->call($indexer)
            ->everyMinute()
            ->name('Index post to elastic')
            ->appendOutputTo(storage_path('logs/schedule.log'));
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
