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

        $schedule->command('telescope:prune --hours=1')->hourly();

        if ($setting->compareStrict('post.reverse', false)) {
            $schedule->command('post:reverser --item=3')
                ->everyTenMinutes()
                ->between('7:00', '22:00')
                ->appendOutputTo(storage_path('logs/schedule.log'));
        }

        // Re-index phone for every 2 hours 
        // Not best solution but honest work
        // TODO fix remove after upgrade to mysql database
        $schedule->call(function () {

            Post::chunk(2000, function (Collection $posts) {
                $posts->each(function (Post $post) {
                    if ($post->phone) {
                        TrackingPost::findByPhoneOrCreate($post->phone)->tracking();
                    }
                });
            });

        })->everyTwoHours();
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
