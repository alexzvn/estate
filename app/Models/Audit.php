<?php

namespace App\Models;

use App\Models\Traits\CacheDefault;
use Illuminate\Support\Carbon;
use App\Models\Traits\CanFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Audit as AuditingAudit;
use OwenIt\Auditing\Contracts\Audit as ContractsAudit;

class Audit extends Model implements ContractsAudit
{
    use AuditingAudit, CanFilter, CacheDefault;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'old_values'   => 'json',
        'new_values'   => 'json',
        // Note: Please do not add 'auditable_id' in here, as it will break non-integer PK models
    ];

    public function filterUser(Builder $builder, $value)
    {
        return $builder->where('user_id', $value);
    }

    public function filterFrom(Builder $builder, $date)
    {
        if ($date = strtotime($date)) {
            $builder->where('created_at', '>=', Carbon::createFromTimestamp($date)->startOfDay());
        }

        return $builder;
    }

    public function filterTo(Builder $builder, $date)
    {
        if ($date = strtotime($date)) {
            $builder->where('created_at', '<=', Carbon::createFromTimestamp($date)->endOfDay());
        }

        return $builder;
    }
}
