<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Order extends Model
{
    public const DISCOUNT_PERCENT = 1;

    public const DISCOUNT_NORMAL  = 2;

    public const PENDING = 1;

    public const PAID = 2;

    protected $fillable = [
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

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
