<?php

namespace App\Models;

use App\Models\Location\Province;
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

    protected $casts = ['sms_history' => 'array'];

    protected $fillable = ['phone', 'source', 'export_count'];

    protected $filterable = ['phone', 'source'];

    const NAME = 'danh sách đen';

    public function posts()
    {
        return $this->hasMany(Post::class, 'phone', 'phone');
    }

    public function adder()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'phone', 'phone');
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function filterProvince(Builder $builder, $value)
    {
        return $builder->where('province_id', $value);
    }
}
