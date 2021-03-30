<?php

namespace App\Jobs\Post;

use stdClass;
use App\Models\Post;
use App\Enums\PostStatus;
use App\Models\Blacklist;
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
        return $this->isInBlacklist($post->phone) ||
            $this->containsKeywords($post->content);
    }

    protected function isInBlacklist($phone)
    {
        return Blacklist::wherePhone($phone)->exists();
    }

    /**
     * Should it locked cause contain blacklist keywords
     *
     * @param string $content
     * @return boolean
     */
    protected function containsKeywords($content)
    {
        $keywords = Setting::load()->get('blacklist.keywords', []);

        foreach ($keywords as $keyword) {
            $keyword = '/' . preg_quote($keyword) . '/';

            if (preg_match($keyword, $content)) {
                return true;
            }
        }

        return false;
    }
}
