<?php

namespace App\Console\Commands;

use App\Enums\PostType;
use App\Models\Post;
use Illuminate\Console\Command;

class DayReverser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reverser:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Post::whereType(PostType::Online)
            ->where('day_reverser', false)
            ->whereNotNull('publish_at')
            ->chunkById(2000, function ($posts) {
                $posts->each(function ($post) {
                    $post->day_reverser = true;
                    $post->publish_at = $post->publish_at->addDay();

                    return $post;
                });

                $posts->each(fn($post) => $post->save());
            });
    }
}
