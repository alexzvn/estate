<?php

namespace App\Models\ScoutFilter;

use App\Models\User;
use Laravel\Scout\Builder;
use Illuminate\Support\Carbon;

class UserFilter extends Filter
{
    public function filterQuery(Builder $builder)
    {
        $builder->orderBy('created_at', 'desc');
    }

    public function filterOnline(Builder $builder)
    {
        return $builder
            ->whereExists('session_id')
            ->where('last_seen', '>=', now()->subMinutes(User::SESSION_TIMEOUT));
    }

    public function filterPhone($builder, $phone)
    {
        return $builder->where('phone', $phone);
    }

    public function filterTo($builder, $time)
    {
        $builder->where(
            'created_at', '<=', Carbon::createFromFormat('d/m/Y', $time)->endOfDay()
        );
    }

    public function filterFrom($builder, $time)
    {
        $builder->where(
            'created_at', '>=', Carbon::createFromFormat('d/m/Y', $time)->startOfDay()
        );
    }

    public function filterSupporter($builder, $support)
    {
        return $builder->where('supporter_id', $support);
    }

    public function filterSpendZero($builder)
    {
        $builder->where('order.total', '<=', 1);
    }

    public function filterSpendMore($builder)
    {
        $builder->where('order.total', '>', 0);
    }

    public function filterNeverLogin($builder)
    {
        $builder->where('has_login', false);
    }

    public function filterNeverReadPostBefore($builder)
    {
        $builder->where('post.seen', false);
    }

    protected function filterStatus(Builder $builder, $status)
    {
        switch ($status) {
            case User::BANNED: return $builder->whereNotNull('banned_at');
            case User::VERIFIED: return $builder->whereNotNull('phone_verified_at');
            case User::UNVERIFIED: return $builder->whereNull('phone_verified_at');
            case User::ONLINE: return $this->filterOnline($builder);
            case User::SPEND_ZERO: return $this->filterSpendZero($builder);
            case User::SPEND_MORE: return $this->filterSpendMore($builder);
            case User::NEVER_LOGIN_BEFORE: return $this->filterNeverLogin($builder);
            case User::NEVER_READ_POST_BEFORE: return $this->filterNeverReadPostBefore($builder);
        }
    }
}
