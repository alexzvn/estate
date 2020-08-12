<?php

namespace App\Observers;

use App\Enums\PostMeta as EnumsPostMeta;
use App\Models\PostMeta;
use App\Models\TrackingPost;

class MetaObserver
{
    /**
     * Handle the post meta "created" event.
     *
     * @param  \App\Models\PostMeta  $postMeta
     * @return void
     */
    public function created(PostMeta $postMeta)
    {
        $this->trackingPost($postMeta);
    }

    /**
     * Handle the post meta "updated" event.
     *
     * @param  \App\Models\PostMeta  $postMeta
     * @return void
     */
    public function updated(PostMeta $postMeta)
    {
        $this->trackingPost($postMeta);
    }

    /**
     * Handle the post meta "deleted" event.
     *
     * @param  \App\Models\PostMeta  $postMeta
     * @return void
     */
    public function deleted(PostMeta $postMeta)
    {
        $this->trackingPost($postMeta);
    }

    /**
     * Handle the post meta "restored" event.
     *
     * @param  \App\Models\PostMeta  $postMeta
     * @return void
     */
    public function restored(PostMeta $postMeta)
    {
        $this->trackingPost($postMeta);
    }

    /**
     * Handle the post meta "force deleted" event.
     *
     * @param  \App\Models\PostMeta  $postMeta
     * @return void
     */
    public function forceDeleted(PostMeta $postMeta)
    {
        $this->trackingPost($postMeta);
    }

    protected function trackingPost(PostMeta $postMeta)
    {
        if ($postMeta->name !== EnumsPostMeta::Phone && ! empty($postMeta->value)) {
            return;
        }

        TrackingPost::findByPhoneOrCreate($postMeta->value)->tracking();
    }
}
