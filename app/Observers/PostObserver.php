<?php

namespace App\Observers;

use App\Models\Keyword;
use App\Models\Post;
use App\Models\TrackingPost;

class PostObserver
{
    /**
     * Handle the post "created" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function created(Post $post)
    {
        $this->index($post);
    }

    /**
     * Handle the post "updated" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function updated(Post $post)
    {
        $this->index($post);
    }

    /**
     * Handle the post "deleted" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function deleted(Post $post)
    {
        $this->tracking($post);
    }

    /**
     * Handle the post "restored" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function restored(Post $post)
    {
        $this->index($post);
    }

    /**
     * Handle the post "force deleted" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function forceDeleted(Post $post)
    {
        $this->tracking($post);
    }

    protected function index(Post $post)
    {
        Post::withoutEvents(function () use ($post) {
            $post->index();
        });

        $this->tracking($post);
    }

    public function tracking(Post $post)
    {
        if ($post->phone) {
            TrackingPost::findByPhoneOrCreate($post->phone)->tracking();
        }
    }
}
