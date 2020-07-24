<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Builder;

class Subscription extends Model
{
    protected $fillable = ['expires_at'];

    protected $dates = ['expires_at'];

    public function plan()
    {
        return $this->hasOne(Plan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isUnlimited()
    {
        return $this->expires_at === null;
    }

    public function scopeActive(Builder $builder)
    {
        return $builder->where('expires_at', '<=', now())
                       ->orWhereNotNull('expires_at');
    }

    public function scopeUnlimited(Builder $builder)
    {
        return $builder->whereNull('expires_at');
    }

    public function scopeLimited(Builder $builder)
    {
        return $builder->whereNotNull('expires_at');
    }
}
