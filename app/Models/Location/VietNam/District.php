<?php

namespace App\Models\Location\VietNam;

use Jenssegers\Mongodb\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['name', 'type'];

    protected $hidden = ['updated_at', 'created_at'];

    public function provinces()
    {
        return $this->belongsTo(Province::class);
    }

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }
}
