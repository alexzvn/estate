<?php

namespace App\Models;

use App\Models\Traits\HasNote;
use Illuminate\Support\Carbon;
use App\Models\Traits\CanFilter;
use App\Contracts\Models\CanNote;
use App\Elastic\OrderIndexer;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Auditable as TraitsAuditable;
use App\Models\Traits\CacheDefault;
use ScoutElastic\Searchable;

class Order extends Model implements CanNote, Auditable
{
    use CacheDefault, Searchable;
    use SoftDeletes, HasNote, CanFilter, TraitsAuditable;

    protected $indexConfigurator = OrderIndexer::class;

    public const DISCOUNT_PERCENT = 1;

    public const DISCOUNT_NORMAL  = 2;

    public const PENDING = 1;

    public const PAID = 2;

    const NAME = 'đơn hàng';

    protected $mapping = [
        'properties' => [
            'plan_name'      => ['type' => 'text'],
            'manual'         => ['type' => 'boolean'],
            'verified'       => ['type' => 'boolean'],
            'customer_name'  => ['type' => 'text'],
            'customer_phone' => ['type' => 'text'],
            'activate_at'    => ['type' => 'date'],
            'expires_at'     => ['type' => 'date'],
            'updated_at'     => ['type' => 'date'],
            'created_at'     => ['type' => 'date'],
        ]
    ];


    protected $fillable = [
        'manual',
        'month',
        'price',
        'status',
        'verified',
        'discount',
        'discount_type',
        'total',
        'activate_at',
        'expires_at',
    ];

    protected $casts = [
        'manual'        => 'boolean',
        'verified'      => 'boolean',
        'discount_type' => 'integer',
        'price'         => 'float',
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

    public function sumMonthPrice()
    {
        $price = $this->plans->reduce(function ($carry, $plan) {
            return $carry + $plan->price;
        }, 0);

        return $price * $this->month;
    }

    public function toSearchableArray()
    {
        $this->load(['plans', 'creator', 'verifier', 'customer']);

        return array_merge($this->toArray(), [
            'plan_name'     => $this->plans->map(fn($p) => $p->name)->join(', '),
            'customer_name' => $this->customer->name ?? '',
            'customer_phone' => $this->customer->phone ?? '',
        ]);
    }
}
