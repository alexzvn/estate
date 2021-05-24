<?php

namespace App\Models\Traits;

use App\Models\Note;

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

    public function writeNote(string $content = '')
    {
        if (! $note = $this->note) {
            $note = new Note(compact('content'));
        }

        $note->fill(compact('content'));

        return $this->note()->save($note);
    }
}
