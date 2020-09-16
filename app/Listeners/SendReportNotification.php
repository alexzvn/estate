<?php

namespace App\Listeners;

use App\Events\Post\UserReport;
use App\Notifications\NewReport as NotificationsNewReport;
use App\Repository\Permission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendReportNotification
{
    /**
     * Handle the event.
     *
     * @param  NewReport  $event
     * @return void
     */
    public function handle(UserReport $event)
    {
        Notification::send(
            Permission::findUsersHasPermission('manager.notification.post.report'),
            new NotificationsNewReport($event->post(),$event->reporter())
        );
    }
}
