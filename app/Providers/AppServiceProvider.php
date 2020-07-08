<?php

namespace App\Providers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.debug')) {
            \DB::connection( 'mongodb' )->enableQueryLog();
        }

        //$this->registerPolicies();

        Gate::before(function (User $user, $ability) {
            return $user->hasPermissionTo('all') ? true : null;
        });
    }
}
