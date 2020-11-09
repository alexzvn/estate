<?php

namespace App\Models\Location;

use App\Models\Traits\CacheDefault;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use CacheDefault;

    protected $fillable = ['name', 'type'];

    protected $hidden = ['updated_at', 'created_at'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }
}
