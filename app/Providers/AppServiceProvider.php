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
        Gate::before(function (User $user, $ability) {
            return $user->hasPermissionTo('*') ? true : null;
        });

        try {
            view()->share('setting', app(Setting::class));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
