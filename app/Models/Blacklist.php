<?php

namespace App\Models;

use App\Models\Traits\CanFilter;
use App\Models\Traits\HasNote;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;

class Blacklist extends Model
{
    use HasNote, CanFilter;

    protected $fillable = ['phone'];

    public function filterPhone(Builder $builder, $value)
    {
        return $builder->where('phone', $value);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'phone', 'phone');
    }
}
