<?php

namespace App\Observers;

use App\Enums\PostStatus;
use App\Models\Blacklist;
use App\Models\Post;

class BlacklistObserver
{
    /**
     * Handle the blacklist "created" event.
     *
     * @param  \App\Models\Blacklist  $blacklist
     * @return void
     */
    public function created(Blacklist $blacklist)
    {
        Post::filterRequest(['phone' => $blacklist->phone])->update([
            'status' => PostStatus::Locked
        ]);
    }

    /**
     * Handle the blacklist "deleted" event.
     *
     * @param  \App\Models\Blacklist  $blacklist
     * @return void
     */
    public function deleted(Blacklist $blacklist)
    {
        Post::filterRequest(['phone' => $blacklist->phone])
        ->where('status', PostStatus::Locked)
        ->update([
            'status' => PostStatus::Published
        ]);
    }
}