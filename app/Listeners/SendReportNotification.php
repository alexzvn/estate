<?php

namespace App\Listeners;

use App\Events\Post\UserReport;
use App\Models\Permission;
use App\Models\User;
use App\Notifications\NewReport as NotificationsNewReport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendReportNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewReport  $event
     * @return void
     */
    public function handle(UserReport $event)
    {
        $notification = new NotificationsNewReport(
            $event->post(),
            $event->reporter()
        );

        Notification::send($this->getUsers(), $notification);

        // $this->getUsers()->each(function (User $user) use ($notification)
        // {
        //     $user->notify($notification);
        // });
    }

    /**
     * Get list users
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsers()
    {
        $staffGroup = Permission::findByName('manager.notification.post.report')->users;

        $adminGroup = Permission::findByName('*')->users;

        return $adminGroup->push(...$staffGroup)->unique('id');
    }
}
