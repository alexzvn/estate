<?php

namespace App\Models;

use Illuminate\Http\Request;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function scopeParentOnly(Builder $builder)
    {
        return $builder->where('parent_id', null);
    }

    /**
     * Get parent category relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get children category relationship
     *
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
