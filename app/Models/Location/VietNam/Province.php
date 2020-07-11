<?php

namespace App\Models\Location\VietNam;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Builder;

class Province extends Model
{
    protected $fillable = ['name', 'type'];

    protected $hidden = ['updated_at', 'created_at'];

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function scopeActive(Builder $builder)
    {
        $builder->where('active', true);
    }
}
