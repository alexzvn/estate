<?php

namespace App\Contracts\Models;

interface CanNote
{
    /**
     * Get Note
     *
     * @return string|null
     */
    public function readNote();

    /**
     * Write Note
     *
     * @param string $content
     * @return void
     */
    public function writeNote(string $content = '');
}