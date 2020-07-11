<?php

namespace App\Models\Location\VietNam;

use Jenssegers\Mongodb\Eloquent\Model;

class Ward extends Model
{
    protected $fillable = ['name', 'type'];

    public function provinces()
    {
        return $this->belongsTo(Province::class);
    }
}
