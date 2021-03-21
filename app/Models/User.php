<?php

namespace App\Models;

use App\Enums\Role as Type;
use App\Models\Traits\CanFilter;
use App\Models\Traits\CanVerifyPhone;
use Maklad\Permission\Traits\HasRoles;
use App\Contracts\Auth\MustVerifyPhone;
use App\Models\Location\Province;
use App\Models\Traits\Auditable as TraitsAuditable;
use App\Models\Traits\CanSearch;
use App\Models\Traits\HasNote;
use App\Models\Traits\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use OwenIt\Auditing\Contracts\Auditable;

// use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyPhone, Auditable
{
    use Notifiable, TraitsAuditable, HasNote;
    use HasRoles, CanVerifyPhone, CanFilter, CanSearch;

    const BANNED = 'banned';

    const VERIFIED = 'verified';

    const UNVERIFIED = 'unverified';

    const SPEND_ZERO = 'spend_zero';

    const SPEND_MORE = 'spend_more';

    const NEVER_LOGIN_BEFORE = 'never_login_before';

    const NEVER_READ_POST_BEFORE = 'never_read_post_before';

    const ONLINE = 'online';

    const NAME = 'người dùng';

    /**
     * Define timeout for recent session in minutes
     * Used for check only one auth session at time
     */
    public const SESSION_TIMEOUT = 30;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime'
    ];

    protected $dates = [
        'last_seen',
        'banned_at',
        'birthday'
    ];

    public function provinces()
    {
        return $this->belongsToMany(Province::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function supporter()
    {
        return $this->belongsTo(User::class, 'supporter_id');
    }

    public function canSupport(User $customer = null)
    {
        return (!is_null($customer) && $this->id === $customer->supporter_id) ||
            $this->can('manager.customer.view.all');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function report()
    {
        return $this->hasMany(Report::class);
    }

    public function smsTemplates()
    {
        return $this->hasMany(SmsTemplate::class);
    }

    public function isBanned()
    {
        return ! empty($this->banned_at);
    }

    public function ban()
    {
        return $this->forceFill(['banned_at' => now()])->save();
    }

    public function pardon()
    {
        return $this->forceFill(['banned_at' => null])->save();
    }

    public function isAdmin()
    {
        return $this->hasRole(Type::SuperAdmin);
    }

    public function blacklistPosts()
    {
        return $this->belongsToMany(Post::class, null, 'user_blacklist_ids', 'post_blacklist_ids');
    }

    public function savePosts()
    {
        return $this->belongsToMany(Post::class, null, 'user_save_ids', 'post_save_ids');
    }

    public function markPhoneAsNotVerified()
    {
        return $this->forceFill([
            'phone_verified_at' => null,
        ])->save();
    }

    public function emptySession()
    {
        $this->forceFill(['session_id' => null])->save();
    }

    public function hasDifferenceOnline()
    {
        return (
            ! empty($this->session_id) &&
            $this->session_id !== request()->session()->getId() &&
            isset($this->last_seen) &&
            now()->lessThan($this->last_seen->addMinutes(self::SESSION_TIMEOUT))
        );
    }

    public function isOnline()
    {
        return (
            isset($this->session_id) &&
            isset($this->last_seen) &&
            now()->lessThan($this->last_seen->addMinutes(self::SESSION_TIMEOUT))
        );
    }

    public function scopeOnline(Builder $builder)
    {
        return $builder
        ->whereNotNull('session_id')
        ->where('last_seen', '>=', now()->subMinutes(static::SESSION_TIMEOUT));
    }

    public function scopeOnlyCustomer(Builder $builder)
    {
        return $builder->whereHas('roles', function (Builder $builder)
        {
            $builder->where('customer', true);
        });
    }

    public function scopeSpendZero(Builder $builder)
    {
        return $builder->whereDoesntHave('orders', function (Builder $builder)
        {
            $builder->where('after_discount_price', '>', 0);
        });
    }

    public function scopeSpendMore(Builder $builder)
    {
        return $builder->whereHas('orders', function (Builder $builder)
        {
            $builder->where('after_discount_price', '>', 0);
        });
    }

    public function scopeNeverLogin(Builder $builder)
    {
        return $builder->whereDoesntHave('logs', function (Builder $builder)
        {
            $builder->where('content', 'regexp', '/^(Đã đăng nhập)/');
        });
    }

    public function scopeNeverReadPostBefore(Builder $builder)
    {
        return $builder->whereDoesntHave('logs', function (Builder $builder)
        {
            $builder->where('content', 'regexp', '/^(Đã xem tin)/');
        });
    }

    protected function filterQuery(Builder $builder, $value)
    {
        $builder = $this->scopeSearch($builder, $value);
        return $this->scopeOrderByScore($builder);
    }

    protected function filterRoles(Builder $builder, $roles)
    {
        $roles = is_string($roles) ? explode(',', $roles) : $roles;

        return $builder->whereHas('roles', function (Builder $builder) use ($roles)
        {
            foreach ($roles as $role) {
                $builder->orWhere('_id', $role);
            }
        });
    }

    protected function filterPhone(Builder $builder, $phone)
    {
        if (preg_match('/^[0-9]+$/', $phone)) {
            return $builder->where('phone', 'like', "%$phone%");
        }

        return $builder->where('phone', $phone);
    }

    protected function filterSupporter(Builder $builder, $support)
    {
        return $builder->where('supporter_id', $support);
    }

    protected function filterTo(Builder $builder, $time)
    {
        $builder->where(
            'created_at', '<=', Carbon::createFromFormat('d/m/Y', $time)->endOfDay()
        );
    }

    protected function filterFrom(Builder $builder, $time)
    {
        $builder->where(
            'created_at', '>=', Carbon::createFromFormat('d/m/Y', $time)->startOfDay()
        );
    }

    protected function filterStatus(Builder $builder, $status)
    {
        switch ($status) {
            case static::BANNED: return $builder->whereNotNull('banned_at');
            case static::VERIFIED: return $builder->whereNotNull('phone_verified_at');
            case static::UNVERIFIED: return $builder->whereNull('phone_verified_at');
            case static::ONLINE: return $this->scopeOnline($builder);
            case static::SPEND_ZERO: return $this->scopeSpendZero($builder);
            case static::SPEND_MORE: return $this->scopeSpendMore($builder);
            case static::NEVER_LOGIN_BEFORE: return $this->scopeNeverLogin($builder);
            case static::NEVER_READ_POST_BEFORE: return $this->scopeNeverReadPostBefore($builder);
        }
    }

    public function setPasswordAttribute($password)
    {
        if ($password === null || $password === '') return;

        if (Hash::info($password)['algo'] === '2y') {
            return $this->attributes['password'] = $password;
        }

        return $this->attributes['password'] = Hash::make($password);
    }

    public function setPhoneAttribute($phone)
    {
        $this->attributes['phone'] = str_replace(['.', ' '], '', $phone);
    }

    public static function getStatusKeyName()
    {
        return [
            static::BANNED => 'Bị khóa',
            static::VERIFIED => 'Đã xác thực',
            static::UNVERIFIED => 'Chưa xác thực',
            static::ONLINE => 'Đang online',
            static::SPEND_ZERO => 'Tài khoản 0đ',
            static::SPEND_MORE => 'Tài khoản trên 0đ',
            static::NEVER_LOGIN_BEFORE => 'Chưa đăng nhập lần nào',
            static::NEVER_READ_POST_BEFORE => 'Chưa xem tin nào'
        ];
    }

    public function getIndexDocumentData()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ];
    }
}
