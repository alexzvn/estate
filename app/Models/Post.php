<?php

namespace App\Models;

use App\EmptyClass;
use Illuminate\Support\Str;
use App\Enums\PostMeta as Meta;
use App\Enums\PostStatus;
use App\Models\Traits\CanFilter;
use App\Models\Traits\ElasticquentSearch;
use Elasticquent\ElasticquentTrait;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes, CanFilter, ElasticquentTrait;
    use ElasticquentSearch;

    protected $fillable = [
        'content', 'title', 'type', 'status'
    ];

    protected $mappingProperties = [
        'title' => [
          'type' => 'text',
          "analyzer" => "standard",
        ],
        'content' => [
          'type' => 'text',
          "analyzer" => "standard",
        ]
    ];

    protected $dates = [
        'publish_at'
    ];

    public function getIndexDocumentData()
    {
        $this->getKey();
        $data = $this->toArray();
        unset($data['_id']);

        return $data;
    }

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
        return $this->belongsToMany(Category::class);
    }

    public function loadMeta()
    {
        $metaEnum   = collect(Meta::toArray())->flip();
        $this->meta = new EmptyClass;

        $this->metas->each(function ($meta) use ($metaEnum)
        {
            if (isset($metaEnum[$meta->name])) {
                $this->meta->{Str::camel($metaEnum[$meta->name])} = $meta;
            }
        });

        return $this;
    }

    public function scopePublished(Builder $builder)
    {
        return $builder->where('status', (string) PostStatus::Published)->whereNotNull('publish_at');
    }

    public function filterType(Builder $builder, $type)
    {
        return $builder->where('type', $type);
    }

    public function filterCategories(Builder $builder, $values)
    {
        $values = is_string($values) ? [$values] : $values;

        return $builder->whereHas('categories', function (Builder $q) use ($values)
        {
            $q->whereIn('_id', $values);
        });
    }

    public function filterQuery(Builder $builder, $value)
    {
        return $this->scopeFilterSearch($builder, $value);
    }

    public function filterProvince(Builder $builder, $value)
    {
        return $builder->whereHas('metas', function (Builder $q) use ($value)
        {
            $q->where('name', Meta::Province)->where('value', $value);
        });
    }

    public function filterCity(Builder $builder, $value)
    {
        return $builder->whereHas('metas', function (Builder $q) use ($value)
        {
            $q->where('name', Meta::City)->where('value', $value);
        });
    }

    public function filterDistrict(Builder $builder, $value)
    {
        return $builder->whereHas('metas', function (Builder $q) use ($value)
        {
            $q->where('name', Meta::District)->where('value', $value);
        });
    }

    public function filterStreet(Builder $builder, $value)
    {
        return $builder->whereHas('metas', function (Builder $q) use ($value)
        {
            $q->where('name', Meta::Street)->where('value', $value);
        });
    }

    public function filterStatus(Builder $builder, $value)
    {
        return $builder->where('status', $value);
    }
}
