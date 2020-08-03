<?php

namespace App\Console\Commands;

use App\Enums\PostType;
use App\Repository\Post;
use Illuminate\Console\Command;

class ReverserPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post:reverser {--item=3}';

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
     * @return void
     */
    public function handle()
    {
        if ($this->option('item') != -1) {
            $posts = $this->posts()->limit((int) $this->option('item'));
        } else {
            $posts = $this->posts();
        }

        $updated = $posts->get()->each(function ($post)
        {
            $post->forceFill([
                'publish_at' => now(),
                'reverser'   => true
            ])->save();
        });

        $this->info('Updated '. $updated->count() . ' posts. Post reserver left is ' . $posts->count());
    }

    public function posts()
    {
        return Post::where('publish_at', '<', now()->today())->where('created_at', '>=', now()->yesterday())->where('type', PostType::Online)->published();
    }
}
