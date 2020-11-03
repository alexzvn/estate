<?php

namespace App\Models;

use App\Models\Traits\Auditable as TraitsAuditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Category extends Model implements Auditable
{
    use TraitsAuditable;

    protected $fillable = ['name', 'description'];

    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'post_ids', 'plan_ids'
    ];


    const NAME = 'danh mục';

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
