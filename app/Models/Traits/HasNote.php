<?php

namespace App\Models\Traits;

use App\Models\Note;
use App\Models\User;

/**
 * 
 */
trait HasNote
{
    public function note()
    {
        return $this->morphOne(Note::class, 'notable');
    }

    public function readNote()
    {
        return $this->note->content ?? null;
    }

    public function writeNote(string $content = '', User $author = null)
    {
        if (! $note = $this->note) {
            $note = new Note(compact('content'));
        }

        $note->user_id = $author->id ?? null;
        $note->fill(compact('content'));

        return $this->note()->save($note);
    }
}
