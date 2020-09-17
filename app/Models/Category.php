<?php

namespace App\Models;

use App\Models\Traits\Auditable as TraitsAuditable;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Category extends Model implements Auditable
{
    use TraitsAuditable;

    protected $fillable = ['name', 'description'];

    protected $modelName = 'danh mục';

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

    public function removeChildren()
    {
        foreach ($this->children()->get() as $child) {
            $child->forceFill(['parent_id' => null])->save();
        }
    }
}
