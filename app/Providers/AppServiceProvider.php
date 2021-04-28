<?php

namespace App\Providers;

use App\Models\User;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
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

        Paginator::useBootstrap();

        view()->share('setting', $this->app->make(Setting::class));

        $this->registerCollectionMacro();
    }

    public function registerCollectionMacro()
    {
        /**
         * Support elastic index model
         */
        Collection::macro('compress', function (string $prefix = '') {
            return $this->reduce(function (array $carry, $item) use ($prefix) {

                foreach ($item->toArray() as $key => $value) {
                    $value = $item->__get($key);

                    if ($value !== null) {
                        $carry[$prefix . $key][] = $value;
                    }
                }

                return $carry;
            }, []);
        });
    }
}
