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
        $user->subscriptions()->delete();
        $user->logs()->delete();
        $user->posts()->update(['user_id' => null]);
        $user->report()->update(['user_id' => null]);
    }

    protected function index(User $user)
    {
        $dispatcher = User::getEventDispatcher();
        User::unsetEventDispatcher();

        $user->index();

        User::setEventDispatcher($dispatcher);
    }
}
