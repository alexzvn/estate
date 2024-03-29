<?php

namespace App\Models;

use App\Models\Traits\Auditable as TraitsAuditable;
use App\Models\Traits\CacheDefault;
use App\Models\Traits\CanFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Report extends Model implements Auditable
{
    use CanFilter, TraitsAuditable, CacheDefault;

    const NAME = 'báo tin môi giới';

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function filterPhone(Builder $builder, string $phone)
    {
        $builder->orWhereHas('user', function (Builder $builder) use ($phone)
        {
            $builder->where('phone', 'like', "%$phone%");
        });

        $builder->orWhereHas('post', function (Builder $builder) use ($phone)
        {
            $builder->where('phone', $phone);
        });
    }
}
