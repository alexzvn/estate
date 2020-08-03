<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Builder;

class Subscription extends Model
{
    protected $fillable = ['expires_at', 'activate_at'];

    protected $dates = ['expires_at', 'activate_at'];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActivated()
    {
        return  $this->expires_at !== null &&
                $this->expires_at->greaterThan(now()) &&
                ! $this->lock;
    }

    public function scopeActive(Builder $builder)
    {
        return $builder->where('expires_at', '>=', now())
            ->where('lock', '<>', true);
    }

    public function scopeLimited(Builder $builder)
    {
        return $builder->whereNotNull('expires_at');
    }
}
