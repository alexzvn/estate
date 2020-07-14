<?php

namespace App\Models\Location;

use Jenssegers\Mongodb\Eloquent\Model;

class Ward extends Model
{
    protected $fillable = ['name', 'type'];

    protected $hidden = ['updated_at', 'created_at'];

    public function provinces()
    {
        return $this->belongsTo(Province::class);
    }
}
