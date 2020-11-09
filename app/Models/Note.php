<?php

namespace App\Models;

use App\Models\Traits\CacheDefault;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use CacheDefault;

    protected $fillable = ['content'];

    public function notable()
    {
        return $this->morphTo();
    }
}
