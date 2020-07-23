<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Location\Province;
use App\Models\Post;
use App\Observers\CategoryObserver;
use App\Observers\PostObserver;
use App\Observers\ProvinceObserver;
use Illuminate\Support\ServiceProvider;

class RegisterObserverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Category::observe(CategoryObserver::class);
        Province::observe(ProvinceObserver::class);

        Post::observe(PostObserver::class);
    }
}
