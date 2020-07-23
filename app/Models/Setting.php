<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key', 'value', 'preload'
    ];

    protected $casts = [
        'preload' => 'boolean'
    ];

    protected $timestamps = false;
}
