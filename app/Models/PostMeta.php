<?php

namespace App\Models;

use App\Models\Location\District;
use App\Models\Location\Province;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class PostMeta extends Model
{
    use SoftDeletes;

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

    public function trackingPost()
    {
        return $this->hasOne(TrackingPost::class, 'phone', 'value');
    }
}
