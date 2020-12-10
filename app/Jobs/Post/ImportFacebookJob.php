<?php

namespace App\Jobs\Post;

use App\Enums\PostStatus;
use App\Models\Blacklist;
use App\Models\Post;
use App\Services\System\Post\Online;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use stdClass;

class ImportFacebookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(stdClass $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (Online::whereHash($this->post->hash)->exists()) {
            return;
        }

        $post = Online::create((array) $this->post);

        if (! empty($post->phone) && Blacklist::wherePhone($post->phone)->exists()) {
            Post::lockByPhone($post->phone);
        }
    }
}
