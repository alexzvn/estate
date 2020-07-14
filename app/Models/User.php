<?php

namespace App\Models;

use App\Enums\Role as Type;
use App\Models\Traits\CanFilter;
use App\Models\Traits\CanVerifyPhone;
use Maklad\Permission\Traits\HasRoles;
use App\Contracts\Auth\MustVerifyPhone;
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

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function isAdmin()
    {
        return $this->hasRole(Type::SuperAdmin);
    }

    public function hasDifferenceOnline()
    {
        return (
            ! empty($this->session_id) &&
            $this->session_id !== request()->session()->getId() &&
            now()->lessThan($this->last_seen->addMinutes(self::SESSION_TIMEOUT))
        );
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
}
