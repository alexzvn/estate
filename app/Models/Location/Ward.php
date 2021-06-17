<?php

namespace App\Models\Location;

use App\Models\Location;
use Illuminate\Support\Str;

class Ward extends Location
{
    protected $fillable = ['name', 'type'];

    protected $hidden = ['updated_at', 'created_at'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
