<?php

namespace App\Models;

use App\Models\Traits\Auditable as TraitsAuditable;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Builder;
use OwenIt\Auditing\Contracts\Auditable;

class Subscription extends Model implements Auditable
{
    use TraitsAuditable;

    protected $modelName = 'gói khách hàng đang đăng ký';

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
