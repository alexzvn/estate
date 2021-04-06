<?php

namespace App\Models\Location;

use App\Models\Traits\CacheDefault;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Province extends Model
{
    use CacheDefault;

    protected $fillable = ['name', 'type'];

    protected $hidden = ['updated_at', 'created_at'];

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeActive(Builder $builder)
    {
        $builder->where('active', true);
    }
}
