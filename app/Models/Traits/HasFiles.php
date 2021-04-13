<?php

namespace App\Models\Traits;

use App\Models\File;

trait HasFiles
{
    public function files()
    {
        return $this->hasMany(File::class);
    }
}
