<?php

namespace App\Models;

use App\Models\Traits\Auditable as TraitsAuditable;
use Illuminate\Database\Eloquent\Model;
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

    const NAME = 'thiết lập trang web';

    public $timestamps = false;
}
