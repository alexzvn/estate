<?php

namespace App\Providers;

use App\Events\Post\UserReport;
use App\Events\UserRegister;
use App\Listeners\SendNotifyNewUserRegister;
use App\Listeners\SendReportNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        UserReport::class => [
            SendReportNotification::class
        ],

        UserRegister::class => [
            SendNotifyNewUserRegister::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
