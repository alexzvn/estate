<?php

namespace App\Models;

use App\Enums\Role as Type;
use App\Models\Traits\CanFilter;
use App\Models\Traits\CanVerifyPhone;
use Maklad\Permission\Traits\HasRoles;
use App\Contracts\Auth\MustVerifyPhone;
use App\Repository\Role;
use App\Repository\Setting;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Auth\User as Authenticatable;

// use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyPhone
{
    use HasRoles, CanVerifyPhone, CanFilter;

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
        'last_seen'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function isAdmin()
    {
        return $this->hasRole(Type::SuperAdmin);
    }

    public function markPhoneAsNotVerified()
    {
        return $this->forceFill([
            'phone_verified_at' => null,
        ])->save();
    }

    public function hasDifferenceOnline()
    {
        return (
            ! empty($this->session_id) &&
            $this->session_id !== request()->session()->getId() &&
            now()->lessThan($this->last_seen->addMinutes(self::SESSION_TIMEOUT))
        );
    }

    public function scopeOnlyCustomer(Builder $builder)
    {
        return $builder->whereHas('roles', function (Builder $builder)
        {
            $builder->where('customer', true);
        });
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

    public static function booted()
    {
        static::created(function (User $user)
        {
            $role = app(Setting::class)->config('user.role.default');

            if ($role = Role::find($role)) {
                $user->assignRole($role->name);
            }
        });
    }
}
