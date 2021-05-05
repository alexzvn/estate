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
        $schedule->call(function () {
            $thePast = now()->subMinutes(30);

            Post::withTrashed()
                ->where('publish_at', '>', $thePast)
                ->orWhere('created_at', '>', $thePast)
                ->chunk(2000, fn($posts) => $posts->searchable());

        })->everyFifteenMinutes()->name('Index post to elastic');

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

        })->everyTwoHours()->name('index phone in tracking post');
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
