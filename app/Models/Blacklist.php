<?php

namespace App\Models;

use App\Models\Traits\Auditable as TraitsAuditable;
use App\Models\Traits\CacheDefault;
use App\Models\Traits\CanFilter;
use App\Models\Traits\HasNote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Blacklist extends Model implements Auditable
{
    use HasNote, CanFilter, TraitsAuditable, CacheDefault;

    protected $primaryKey = 'phone';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['phone'];

    const NAME = 'danh sách đen';

    public function filterPhone(Builder $builder, $value)
    {
        return $builder->where('phone', $value);
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
