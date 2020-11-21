<?php

namespace App\Models;

use App\Models\Location\Province;
use App\Models\Traits\Auditable as TraitsAuditable;
use App\Models\Traits\CacheDefault;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Plan extends Model implements Auditable
{
    use TraitsAuditable, CacheDefault;

    const NAME = 'gói đăng ký';

    protected $fillable = [
        'name', 'price', 'types'
    ];

    public function provinces()
    {
        return $this->belongsToMany(Province::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function types()
    {
        return $this->hasMany(PlanType::class);
    }

    public function setTypesAttribute($types)
    {
        if (empty($types)) return;

        foreach ($types as $type) {
            $list[]['type'] = $type;
        }

        $this->types()->delete();
        $this->types()->create($list);
    }
}
