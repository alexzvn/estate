<?php

namespace App\Providers;

use App\Models\User;
use App\Repository\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

        Carbon::setToStringFormat('d/m/Y h:iA');

        try {
            view()->share('setting', app(Setting::class));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
