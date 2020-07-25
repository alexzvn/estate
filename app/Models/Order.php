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
        'price', 'after_discount_price','discount', 'discount_type', 'status' ,'verified'
    ];

    protected $casts = [
        'verified' => 'boolean',
        'discount_type' => 'integer',
        'price' => 'float',
    ];

    public function plans()
    {
        return $this->hasMany(Plan::class);
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
