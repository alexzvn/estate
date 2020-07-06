<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'content', 'title', 'type', 'status'
    ];

    protected $dates = [
        'publish_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
