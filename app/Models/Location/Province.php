<?php

namespace App\Models\Location;

use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Province extends Location
{
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

    public function toRegex()
    {
        $name = preg_replace("/^(Tỉnh|Thành Phố)/i", '', $this->name, 1);

        $name = trim($name);

        return "/$name/i";
    }
}
