<?php

namespace App\Observers;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class NoteObserver
{
    /**
     * Handle the models note "created" event.
     *
     * @param  \App\Models\Note  $note
     * @return void
     */
    public function created(Note $note)
    {
        $this->updateAdder($note);
    }

    public function updated(Note $note)
    {
        $this->updateAdder($note);
    }

    protected function updateAdder(Note $note)
    {
        if (! Auth::check()) return;

        Note::withoutAudit(function () use ($note)
        {
            Note::withoutEvents(function () use ($note)
            {
                $note->forceFill(['adder_id' => Auth::id()])->save();
            });
        });
    }
}
