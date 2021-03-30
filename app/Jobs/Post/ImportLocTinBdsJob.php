<?php

namespace App\Jobs\Post;

use App\Enums\PostStatus;
use App\Models\Blacklist;
use App\Services\System\Post\Online;
use Illuminate\Support\Facades\Cache;

class ImportLocTinBdsJob extends ImportPostJob
{
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

        if ($this->shouldLock($post)) {
            $post->fill(['status' => PostStatus::Locked])->save();
        }
    }
}
