<?php

namespace App\Models;

use App\Contracts\Models\CanNote;
use App\Models\Traits\HasNote;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Traits\CanFilter;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model implements CanNote
{
    use SoftDeletes, HasNote, CanFilter;

    public const DISCOUNT_PERCENT = 1;

    public const DISCOUNT_NORMAL  = 2;

    public const PENDING = 1;

    public const PAID = 2;

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

    public function filterSearch(Builder $builder, $query)
    {
        $builder->where(function ($builder) use ($query)
        {
            $this->filterSearchUser($builder, $query);
            $this->filterSearchCustomer($builder, $query);
        });
    }

    public function filterSearchUser(Builder $builder, $query)
    {
        $builder->orWhereHas('creator', function ($q) use ($query)
        {
            $q->where('index_meta', 'like', "%$query%");
        });
    }

    public function filterSearchCustomer(Builder $builder, $query)
    {
        $builder->orWhereHas('customer', function ($q) use ($query)
        {
            $q->where('index_meta', 'like', "%$query%");
        });
    }
}
