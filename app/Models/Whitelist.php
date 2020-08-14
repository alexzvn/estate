<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Whitelist extends Model
{
    protected $fillable = ['phone'];

    public static function findByPhoneOrCreate(string $phone)
    {
        return self::where('phone', $phone)->firstOrCreate([
            'phone' => $phone
        ]);
    }
}
