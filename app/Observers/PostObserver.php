<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\PostMeta;

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
        $this->removeIndex($post);
        $post->metas()->forceDelete();
    }

    protected function index(Post $post)
    {
        $this->indexMeta($post);
        $post->addToIndex();
    }

    protected function removeIndex(Post $post)
    {
        $post->removeFromIndex();
    }

    public function indexMeta(Post $post)
    {
        $metas = $post->metas()->with([
            'province',
            'district'
        ])->get();

        $indexs = $metas->reduce(function ($carry, $meta)
        {
            if ($meta->province) {
                $carry[] = $meta->province->name;
            } elseif ($meta->district) {
                $carry[] = $meta->district->name;
            } else {
                $carry[]   = $meta->value;
            }

            return $carry;
        }, $carry = []);

        $indexs = $post->categories()->get()->reduce(function ($carry, $category)
        {
            $carry[] = $category->name;
            return $carry;
        }, $indexs);

        $dispatcher = Post::getEventDispatcher();
        Post::unsetEventDispatcher();

        $post->forceFill([
            'index_meta' => implode(' ', $indexs) . '.'
        ])->save();

        Post::setEventDispatcher($dispatcher);
    }
}
