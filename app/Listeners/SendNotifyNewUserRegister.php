<?php

namespace App\Listeners;

use App\Events\UserRegister;
use App\Notifications\NewUserRegister;
use App\Repository\Permission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendNotifyNewUserRegister
{
    /**
     * Handle the event.
     *
     * @param  UserRegister  $event
     * @return void
     */
    public function handle(UserRegister $event)
    {
        $users = Permission::findUsersHasPermission('manager.notification.user.register');

        Notification::send($users, new NewUserRegister($event->user));
    }
}
