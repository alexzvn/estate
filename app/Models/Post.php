<?php

namespace App\Models;

use App\Elastic\PostIndexer;
use App\Enums\PostStatus;
use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Traits\Auditable as TraitsAuditable;
use App\Models\Traits\CacheDefault;
use App\Models\Traits\CanFilter;
use App\Models\Traits\HasFiles;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use ScoutElastic\Searchable;
use OwenIt\Auditing\Contracts\Auditable;

class Post extends Model implements Auditable
{
    use TraitsAuditable, CacheDefault, Searchable;
    use SoftDeletes, CanFilter, HasFiles;

    const NAME = 'tin';

    protected $indexConfigurator = PostIndexer::class;

    protected $filterable = [
        'verifier_id',
        'status',
        'type',
        'phone'
    ];

    protected $fillable = [
        'content',
        'title',
        'type',
        'status',
        'phone',
        'price',
        'commission',
        'extra',
    ];

    protected $dates = [
        'publish_at'
    ];

    protected $casts = [
        'extra' => 'json',
        'reverser' => 'boolean',
        'approve_fee' => 'boolean',
        'day_reverser' => 'boolean',
    ];

    protected $mapping = [
        'properties' => [
            'title'        => ['type' => 'text'],
            'content'      => ['type' => 'text'],
            'reverser'     => ['type' => 'boolean'],
            'approve_fee'  => ['type' => 'boolean'],
            'phone'        => ['type' => 'text'],
            'meta'         => ['type' => 'text'],
            'extra'        => ['type' => 'object'],
            'publish_at'   => ['type' => 'date'],
            'created_at'   => ['type' => 'date'],
            'updated_at'   => ['type' => 'date'],
            'day_reverser' => ['type' => 'boolean'],
        ]
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function report()
    {
        return $this->hasOne(Report::class);
    }

    public function tracking()
    {
        return $this->belongsTo(TrackingPost::class, 'phone', 'phone');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }

    public function whitelist()
    {
        return $this->belongsTo(Whitelist::class, 'phone', 'phone');
    }

    public function blacklist()
    {
        return $this->belongsTo(Blacklist::class, 'phone', 'phone');
    }

    public function isLocked()
    {
        return $this->status === PostStatus::Locked;
    }

    public function lock()
    {
        return $this->fill(['status' => PostStatus::Locked])->save();
    }

    public function publish()
    {
        return $this->fill(['status' => PostStatus::Published])->save();
    }

    public function scopePublished(Builder $builder)
    {
        return $builder
            ->whereNotNull('publish_at')
            ->where('status', PostStatus::Published);
    }

    public function scopeNewest(Builder $builder)
    {
        return $builder->orderBy('publish_at', 'desc');
    }

    public function scopePending(Builder $builder)
    {
        $builder->where('status', PostStatus::Pending());
    }

    public function scopeWithoutWhitelist(Builder $builder)
    {
        return $builder->whereDoesntHave('whitelist');
    }

    public function scopeWithoutBlacklist(Builder $builder)
    {
        $builder->whereDoesntHave('blacklist');
    }

    public static function lockByPhone($phones)
    {
        if (is_string($phones)) {
            $phones = [$phones];
        }

        if (empty($phones)) {
            return;
        }

        return updater(static::whereIn('phone', $phones))->update([
            'status' => PostStatus::Locked
        ]);
    }

    public function filterFrom(Builder $builder, $date)
    {
        if ($date = strtotime($date)) {
            $builder->where('publish_at', '>=', Carbon::createFromTimestamp($date)->startOfDay());
        }

        return $builder;
    }

    public function filterTo(Builder $builder, $date)
    {
        if ($date = strtotime($date)) {
            $builder->where('publish_at', '<=', Carbon::createFromTimestamp($date)->endOfDay());
        }

        return $builder;
    }

    public function filterCategories(Builder $builder, $values)
    {
        $values = is_string($values) ? [$values] : $values;

        return $builder->whereHas('categories', function (Builder $q) use ($values)
        {
            $q->whereIn('id', $values);
        });
    }

    public function filterQuery(Builder $builder, $value)
    {
        // return $this->scopeSearch($builder, $value);
    }

    public function filterProvince(Builder $builder, $value)
    {
        return $builder->where('province_id', $value);
    }

    public function filterProvinces(Builder $builder, $value)
    {
        return $builder->whereIn('province_id', $value);
    }

    public function filterDistrict(Builder $builder, $value)
    {
        return $builder->where('district_id', $value);
    }

    public function filterSource(Builder $builder, $source)
    {
        return $builder->whereSource((int) $source);
    }

    public function filterPrice(Builder $builder, $price)
    {
        if (! is_string($price)) {
            return $builder;
        }

        $price = explode('-', $price);
        $min = (int) $price[0] ?? 0;
        $max = (int) $price[1] ?? 0;

        if ($min) {
            $builder = $this->filterMinPrice($builder, $min);
        }

        if ($max) {
            $builder = $this->filterMaxPrice($builder, $max);
        }

        return $builder;
    }

    public function filterMinPrice(Builder $builder, int $price)
    {
        return $builder->where('price', '>=', $price);
    }

    public function filterMaxPrice(Builder $builder, int $price)
    {
        return $builder->where('price', '<=', $price);
    }

    public function toSearchableArray()
    {
        $post = array_merge($this->toArray(), [
            'content'     => remove_tags($this->content),
            'province'    => $this->province->name ?? null,
            'district'    => $this->district->name ?? null,
            'category_id' => $this->categories()->first()->id ?? null,
            'price'       => $this->price > 100_000_000_000 ? null : $this->price
        ]);

        unset($post['day_reverser']);
        unset($post['extra']);

        return $post;
    }
}
