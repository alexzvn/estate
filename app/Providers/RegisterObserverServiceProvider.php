<?php

namespace App\Providers;

use App\Models\Blacklist;
use App\Models\Post;
use App\Models\PostMeta;
use App\Models\User;
use App\Observers\BlacklistObserver;
use App\Observers\MetaObserver;
use App\Observers\PostObserver;
use App\Observers\UserObserver;
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
        Post::observe(PostObserver::class);
        User::observe(UserObserver::class);
        Blacklist::observe(BlacklistObserver::class);
        PostMeta::observe(MetaObserver::class);
    }
}
