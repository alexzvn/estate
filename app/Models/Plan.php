<?php

namespace App\Models;

use App\Models\Location\Province;
use App\Models\Traits\Auditable as TraitsAuditable;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Plan extends Model implements Auditable
{
    use TraitsAuditable;

    const NAME = 'gói đăng ký';

    protected $fillable = [
        'name', 'price', 'types', 'renewable'
    ];

    protected $casts = [
        'renewable' => 'boolean'
    ];

    public function scopeForCustomer(Builder $builder)
    {
        return $builder->whereRenewable(true);
    }

    public function provinces()
    {
        return $this->belongsToMany(Province::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
