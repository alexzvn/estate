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
        dispatch(fn() => $keyword->lock());
    }

    /**
     * Handle the Keyword "deleted" event.
     *
     * @param  \App\Models\Keyword  $keyword
     * @return void
     */
    public function deleted(Keyword $keyword)
    {
        // Keyword no longer exists in DB after delete so use serialize instead
        $keyword = serialize($keyword);

        dispatch(fn() => unserialize($keyword)->unlock());
    }

}
