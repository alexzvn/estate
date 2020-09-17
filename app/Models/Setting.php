<?php

namespace App\Models;

use App\Models\Traits\Auditable as TraitsAuditable;
use Jenssegers\Mongodb\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Setting extends Model implements Auditable
{
    use TraitsAuditable;

    protected $fillable = [
        'key', 'value', 'preload'
    ];

    protected $casts = [
        'preload' => 'boolean'
    ];

    public $timestamps = false;
}
