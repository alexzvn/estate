<?php

namespace App\Observers;

use App\Models\User;
use App\Repository\Role;
use App\Setting;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        $role = app(Setting::class)->get('user.role.default');

        if ($role = Role::find($role)) {
            $user->assignRole($role->name);
        }
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        $user->subscriptions()->delete();
        $user->logs()->delete();
        $user->audits()->delete();
    }
}
