<?php

namespace App\Providers;

use App\Models\Blacklist;
use App\Models\Keyword;
use App\Models\Message;
use App\Models\Note;
use App\Models\Post;
use App\Models\User;
use App\Observers\BlacklistObserver;
use App\Observers\KeywordObserver;
use App\Observers\MessageObserver;
use App\Observers\NoteObserver;
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
        Keyword::observe(KeywordObserver::class);
        Message::observe(MessageObserver::class);
    }
}
