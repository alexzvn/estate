<?php

namespace App\Models;

use App\Models\Location\District;
use App\Models\Location\Province;
use Jenssegers\Mongodb\Eloquent\Model;

class PostMeta extends Model
{
    protected $fillable = ['name', 'value'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'value');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'value');
    }
}
