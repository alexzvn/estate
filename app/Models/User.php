<?php

namespace App\Models;

use App\Enums\Role;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Maklad\Permission\Traits\HasRoles;

// use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasRoles;
    
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
        return $this->hasRole(Role::SuperAdmin);
    }

    public function hasDifferenceOnline()
    {
        return (
            ! empty($this->session_id) &&
            $this->session_id !== request()->session()->getId() &&
            now()->lessThan($this->last_seen->addMinutes(self::SESSION_TIMEOUT))
        );
    }
}
