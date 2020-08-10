<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\PostMeta;
use Illuminate\Support\Str;

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
        $this->removeIndex($post);
        $post->metas()->delete();
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
        $post->metas()->forceDelete();
    }

    protected function index(Post $post)
    {
        $this->indexMeta($post);
    }

    protected function removeIndex(Post $post)
    {
        $post->removeFromIndex();
    }

    public function indexMeta(Post $post)
    {

        $dispatcher = Post::getEventDispatcher();
        Post::unsetEventDispatcher();

        $post->index();

        Post::setEventDispatcher($dispatcher);
    }
}
