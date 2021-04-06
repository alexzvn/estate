<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Jenssegers\Mongodb\Eloquent\Model;

class SmsHistory extends Model
{
    protected $fillable = [
        'recipient',
        'content'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class);
    }
}
