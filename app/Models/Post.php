<?php

namespace App\Models;

use App\EmptyClass;
use App\Models\Traits\CanFilter;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Enums\PostMeta as Meta;

class Post extends Model
{
    use SoftDeletes, CanFilter;

    protected $fillable = [
        'content', 'title', 'type', 'status'
    ];

    protected $dates = [
        'publish_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function metas()
    {
        return $this->hasMany(PostMeta::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function getMetas()
    {
        return array_reduce($this->metas, function ($carry, $item)
        {
            $carry->{$item->name} = $item->value;
            return $carry;
        }, new EmptyClass);
    }

    public function filterProvince(Builder $builder, $value)
    {
        return $builder->whereHas(PostMeta::class, function (Builder $q) use ($value)
        {
            $q->where('name', Meta::Province)->where('value', $value);
        });
    }

    public function filterCity(Builder $builder, $value)
    {
        return $builder->whereHas(PostMeta::class, function (Builder $q) use ($value)
        {
            $q->where('name', Meta::City)->where('value', $value);
        });
    }

    public function filterDistrict(Builder $builder, $value)
    {
        return $builder->whereHas(PostMeta::class, function (Builder $q) use ($value)
        {
            $q->where('name', Meta::District)->where('value', $value);
        });
    }

    public function filterStreet(Builder $builder, $value)
    {
        return $builder->whereHas(PostMeta::class, function (Builder $q) use ($value)
        {
            $q->where('name', Meta::Street)->where('value', $value);
        });
    }
}
