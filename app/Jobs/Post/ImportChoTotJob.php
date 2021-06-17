<?php

namespace App\Jobs\Post;

use App\Enums\PostStatus;
use App\Services\System\Post\Online;

class ImportChoTotJob extends ImportPostJob
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

        $post->searchable();
    }
}
