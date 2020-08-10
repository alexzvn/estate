<?php

namespace App\Observers;

use App\Models\User;
use App\Repository\Role;
use App\Repository\Setting;

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
        $role = app(Setting::class)->config('user.role.default');

        if ($role = Role::find($role)) {
            $user->assignRole($role->name);
        }

        $this->index($user);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        $this->index($user);
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        $this->removeIndex($user);
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        $this->index($user);
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        $this->removeIndex($user);
    }

    protected function index(User $user)
    {
        $user->index();
    }

    protected function removeIndex(User $user)
    {
        $user->removeFromIndex();
    }
}
