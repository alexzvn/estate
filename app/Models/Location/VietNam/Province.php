<?php

namespace App\Models\Location\VietNam;

use Jenssegers\Mongodb\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['name', 'type'];

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
