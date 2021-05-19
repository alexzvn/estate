<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['content'];

    protected $casts = [
        'extra' => 'json'
    ];

    public function sender()
    {
        return $this->morphTo();
    }

    public function topic()
    {
        return $this->morphTo();
    }
}
