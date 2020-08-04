<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Report extends Model
{
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
