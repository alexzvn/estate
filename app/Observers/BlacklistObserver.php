<?php

namespace App\Observers;

use App\Enums\PostStatus;
use App\Models\Blacklist;
use App\Models\Post;
use App\Models\Whitelist;

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
        updater(Post::where('phone', $blacklist->phone))
            ->update([
                'status' => PostStatus::Locked
            ]);

        Whitelist::where('phone', $blacklist->phone)->delete();
    }

    /**
     * Handle the blacklist "deleted" event.
     *
     * @param  \App\Models\Blacklist  $blacklist
     * @return void
     */
    public function deleted(Blacklist $blacklist)
    {
        $builder = Post::filter(['phone' => $blacklist->phone])
            ->where('status', PostStatus::Locked);

        updater($builder)->update([
            'status' => PostStatus::Published
        ]);
    }
}
