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
        return $this->hasOne(Note::class);
    }

    public function readNote()
    {
        return $this->note->content ?? null;
    }

    public function writeNote(string $content = '')
    {
        $note = $this->note()->firstOrNew();

        $note->fill(compact('content'))->save();

        return $this->note()->save($note);
    }
}
