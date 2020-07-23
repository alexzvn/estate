<?php

namespace App\Models;

use App\Models\Location\Province;
use Jenssegers\Mongodb\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'name', 'price'
    ];

    public function provinces()
    {
        return $this->belongsToMany(Province::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
