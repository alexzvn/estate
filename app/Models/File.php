<?php

namespace App\Models;

use App\Models\Traits\CacheDefault;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use CacheDefault;

    protected $fillable = ['name', 'path'];
}
