<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostMeta extends Model
{
    protected $fillable = ['name', 'value'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
