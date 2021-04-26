<?php

namespace App\Jobs\Post;

use stdClass;
use App\Models\Post;
use App\Enums\PostStatus;
use App\Models\Blacklist;
use App\Models\Keyword;
use Illuminate\Bus\Queueable;
use App\Services\System\Post\Online;
use App\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(stdClass $post)
    {
        $this->post = $post;
    }

    protected function shouldLock(Post $post)
    {
       $conditions = [
            $this->isInKeyword($post),
            $this->isInBlacklist($post->phone)
       ];

        foreach ($conditions as $value) {
            if ($value) return true;
        }

        return false;
    }

    protected function isInBlacklist($phone)
    {
        return Blacklist::wherePhone($phone)->exists();
    }

    protected function isInKeyword(Post $post)
    {
        $inKeyword = false;
        $keywords = Keyword::all();

        foreach ($keywords as $key) {
            if ($key->test("$post->title $post->content")) {
                $inKeyword = true;

                $key->posts = $key->posts->push($post->id)->unique();

                $key->save();
            }
        }

        return $inKeyword;
    }
}
