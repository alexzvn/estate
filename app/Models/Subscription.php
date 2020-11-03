<?php

namespace App\Models;

use App\Models\Traits\Auditable as TraitsAuditable;
use App\Models\Traits\CanFilter;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Contracts\Auditable;

class Subscription extends Model implements Auditable
{
    use TraitsAuditable, CanFilter;

    const NAME = 'gói khách hàng đang đăng ký';

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

    protected function filterExpires(Builder $builder, $time)
    {
        $time = Carbon::createFromFormat('d/m/Y', $time);

        $builder->whereBetween('expires_at', [$time->startOfDay(), $time->endOfDay()]);
    }

    public function filterExpiresLast(Builder $builder, $days)
    {
        $builder->whereBetween('expires_at', [
            now()->startOfDay(),
            now()->addDays((int) $days)->endOfDay()
        ]);
    }

}
