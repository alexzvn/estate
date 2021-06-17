<?php

namespace App\Models;

use App\Models\Location\Province;
use App\Models\Traits\CacheDefault;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Auditable as TraitsAuditable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Plan extends Model implements Auditable
{
    use TraitsAuditable, CacheDefault, HasJsonRelationships, SoftDeletes;

    const NAME = 'gói đăng ký';

    protected $fillable = [
        'name', 'price', 'types', 'renewable', 'categories', 'provinces'
    ];

    protected $casts = [
        'types' => AsArrayObject::class,
        'categories' => AsArrayObject::class,
        'provinces' => AsArrayObject::class,
        'renewable' => 'boolean'

    ];

    public function scopeForCustomer(Builder $builder)
    {
        return $builder->whereRenewable(true);
    }

    public function provinces()
    {
        return $this->belongsToJson(Province::class, 'provinces');
    }

    public function categories()
    {
        return $this->belongsToJson(Category::class, 'categories');
    }
}
