<?php

namespace App\Observers;

use App\Models\Keyword;

class KeywordObserver
{
    /**
     * Handle the Keyword "created" event.
     *
     * @param  \App\Models\Keyword  $keyword
     * @return void
     */
    public function created(Keyword $keyword)
    {
        $keyword->index();
    }

    /**
     * Handle the Keyword "updated" event.
     *
     * @param  \App\Models\Keyword  $keyword
     * @return void
     */
    public function updated(Keyword $keyword)
    {
        $keyword->lock();
    }

    /**
     * Handle the Keyword "deleting" event.
     *
     * @param  \App\Models\Keyword  $keyword
     * @return void
     */
    public function deleting(Keyword $keyword)
    {
        $keyword->unlock();
    }

}
