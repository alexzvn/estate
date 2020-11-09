<?php

namespace App\Models;

use App\Models\Traits\HasNote;
use Illuminate\Support\Carbon;
use App\Models\Traits\CanFilter;
use App\Contracts\Models\CanNote;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Auditable as TraitsAuditable;
use App\Models\Traits\CacheDefault;
use App\Models\Traits\CanSearch;

class Order extends Model implements CanNote, Auditable
{
    use CacheDefault;
    use SoftDeletes, HasNote, CanFilter, TraitsAuditable, CanSearch;

    public const DISCOUNT_PERCENT = 1;

    public const DISCOUNT_NORMAL  = 2;

    public const PENDING = 1;

    public const PAID = 2;

    const NAME = 'đơn hàng';

    protected $fillable = [
        'manual',
        'month',
        'price',
        'status',
        'verified',
        'discount',
        'discount_type',
        'after_discount_price',
        'activate_at',
        'expires_at',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'discount_type' => 'integer',
        'price' => 'float',
    ];

    protected $dates = [
        'activate_at',
        'expires_at'
    ];

    public function plans()
    {
        return $this->belongsToMany(Plan::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function verify()
    {
        return $this->forceFill([
            'status' => static::PAID,
        ])->save();
    }

    public function isActivated()
    {
        return $this->activate_at !== null;
    }

    public function isPaid()
    {
        return $this->status === static::PAID;
    }

    public function filterQuery(Builder $builder, $query)
    {
        $search = function ($builder) use ($query) {
            $builder->search($query);
        };

        $builder->whereHas('customer', $search);
    }

    public function filterActivatedFrom(Builder $builder, $time)
    {
        return $builder->where(
            'activate_at', '>=', Carbon::createFromFormat('d/m/Y', $time)->startOfDay()
        );
    }

    public function filterActivatedTo(Builder $builder, $time)
    {
        return $builder->where(
            'activate_at', '<=', Carbon::createFromFormat('d/m/Y', $time)->endOfDay()
        );
    }

    public function filterCreator(Builder $builder, $creator)
    {
        return $builder->where('creator_id', $creator);
    }

    public function filterPlan(Builder $builder, $plan)
    {
        return $builder->whereHas('plans', function ($builder) use ($plan)
        {
            $builder->where('id', $plan);
        });
    }

    public function filterPriceGreater(Builder $builder, $value)
    {
        return $builder->where('price', '>=', (int) $value);
    }
}
