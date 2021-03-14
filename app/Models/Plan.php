<?php

namespace App\Models;

use App\Models\Location\Province;
use App\Models\Traits\Auditable as TraitsAuditable;
use App\Models\Traits\CacheDefault;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Plan extends Model implements Auditable
{
    use TraitsAuditable, CacheDefault, HasJsonRelationships;

    const NAME = 'gói đăng ký';

    protected $fillable = [
        'name', 'price', 'types', 'categories', 'provinces'
    ];

    protected $casts = [
        'types' => AsArrayObject::class,
        'categories' => AsArrayObject::class,
        'provinces' => AsArrayObject::class,
    ];

    public function provinces()
    {
        return $this->belongsToJson(Province::class, 'provinces');
    }

    public function categories()
    {
        return $this->belongsToJson(Category::class, 'categories');
    }
}
