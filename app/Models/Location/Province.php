<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
