<?php

namespace App\Providers;

use App\Models\User;
use App\Repository\Setting;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
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
        if (config('app.debug')) {
            DB::connection( 'mongodb' )->enableQueryLog();
        }

        Gate::before(function (User $user, $ability) {
            return $user->hasPermissionTo('*') ? true : null;
        });

        Carbon::setToStringFormat('d/m/Y h:iA');

        Paginator::useBootstrap();

        // view()->share('setting', $this->app->make(Setting::class));
    }
}
