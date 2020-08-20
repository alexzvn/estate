<?php

namespace App\Models;

use App\Contracts\Models\CanNote;
use App\Models\Traits\HasNote;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Order extends Model implements CanNote
{
    use SoftDeletes, HasNote;

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
}
