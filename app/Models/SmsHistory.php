<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;

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
