<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register all helper
     *
     * @return void
     */
    public function boot()
    {
        foreach (glob(app_path('Helpers/*.php')) as $helper) {
            require_once $helper;
        }
    }
}
