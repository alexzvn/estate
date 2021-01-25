<?php

namespace App\Jobs\Post;

use stdClass;
use App\Models\Post;
use App\Enums\PostStatus;
use App\Models\Blacklist;
use Illuminate\Bus\Queueable;
use App\Services\System\Post\Online;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportChoTotJob
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

        if ($this->getBlacklist()->where('phone', $post->phone)->isNotEmpty()) {
            $post->fill(['status' => PostStatus::Locked])->save();
        }
    }

    protected function getBlacklist()
    {
        return Cache::remember('blacklist.phone', now()->addMinute(), function ()
        {
            return Blacklist::all();
        });
    }
}
