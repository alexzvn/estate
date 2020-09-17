<?php

namespace App\Models;

use App\Models\Traits\Auditable as TraitsAuditable;
use Jenssegers\Mongodb\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Whitelist extends Model implements Auditable
{
    use TraitsAuditable;

    protected $fillable = ['phone'];

    const NAME = 'danh sách trắng';

    public static function findByPhoneOrCreate(string $phone)
    {
        return self::where('phone', $phone)->firstOrCreate([
            'phone' => $phone
        ]);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'phone', 'phone');
    }
}
