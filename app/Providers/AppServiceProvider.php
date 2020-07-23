<?php

namespace App\Providers;

use App\Enums\Role;
use App\Models\User;
use App\Repository\Setting;
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
        $this->app->singleton(Setting::class);
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

        Gate::before(function (User $user, $ability) {
            return $user->hasPermissionTo('*') ? true : null;
        });

        

        view()->share('setting', $this->app->make(Setting::class));
    }
}
