<?php

namespace App\Models;

use App\Models\Traits\Auditable as TraitsAuditable;

use App\Models\Traits\CanFilter;
use App\Models\Traits\CacheDefault;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Whitelist extends Model implements Auditable
{

    use TraitsAuditable, CacheDefault, CanFilter;

    protected $primaryKey = 'phone';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['phone'];

    protected $filterable = ['phone'];

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
