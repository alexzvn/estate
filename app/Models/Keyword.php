<?php

namespace App\Models;

use App\Enums\PostStatus;
use App\Jobs\UpdateManyPostJob;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;

class Keyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'count',
        'linear',  // enable linear regex match
        'posts',
        'relative'
    ];

    protected $casts = [
        'posts' => AsCollection::class,
        'linear' => 'boolean',
    ];

    public function toRegex()
    {
        $keyword = $this->makeUnicodeRegex();

        if ($this->linear) {
            return "/$keyword/i";
        }

        return "/(?:^|\W)$keyword(?:$|\W)/i";
    }

    public function toSearchRegex()
    {
        $keyword = $this->makeUnicodeRegex();

        if ($this->linear) {
            return "$keyword";
        }

        return "(?:^|\W)$keyword(?:$|\W)";
    }

    public function lock()
    {
        $updater = new UpdateManyPostJob(function () {
            return Post::whereIn('id', $this->posts)
                ->whereStatus(PostStatus::Published);
        });

        $updater->update(['status' => PostStatus::Locked]);

        $updater->dispatch();

        $this->lockRelative();
    }

    public function unlock()
    {
        $updater = new UpdateManyPostJob(function () {
            return Post::whereStatus(PostStatus::Locked)
                ->whereDoesntHave('blacklist')
                ->whereIn('id', $this->filterKeywords($this->posts));
        });

        $updater->update(['status' => PostStatus::Published]);

        $updater->dispatch();

        $this->unlockRelative();
    }

    /**
     * Test if keyword match given content
     *
     * @param string $content
     * @return boolean
     */
    public function test(string $content)
    {
        return (bool) preg_match($this->toRegex(), $content);
    }

    public function index()
    {
        $posts = Post::where('content', 'regexp', $this->toSearchRegex())
            ->orWhere('title', 'regexp', $this->toSearchRegex())
            ->whereDoesntHave('whitelist')
            ->get();

        $this->fill([
            'count' => $posts->count(),
            'posts' => $posts->map(fn($post) => $post->id)
        ]);

        $this->fill(['relative' => $this->countRelative()])->save();

        $this->lock();
    }

    public function indexPost(Post $post)
    {
        if ($post->whitelist) return;

        if ($this->test("$post->title $post->content")) {
            $this->posts = $this->posts->push($post->id);

        } elseif ($post->isLocked()) {
            $post->publish();
        }

        return $this->fill([
            'count' => $this->posts->count(),
            'posts' => $this->posts
        ])->save();
    }

    protected function lockSingle(Post $post)
    {
        $post->lock();
    }

    protected function unlockSingle(Post $post)
    {
        $isMatch = false;

        foreach (static::all() as $keyword) {
            if ($keyword->is($this)) {
                continue;
            }

            if ($keyword->test("$post->content $post->title")) {
                $isMatch = true;

                break;
            }
        }

        $isMatch === false && $post->publish();
    }

    public function countRelative()
    {
        return $this->getRelativePostId()->count();
    }

    public function lockRelative()
    {
        $updater = new UpdateManyPostJob(function () {
            return  Post::whereIn('phone', $this->getPhones())
                ->whereStatus(PostStatus::Published)
                ->whereDoesntHave('whitelist');
        });

        $updater->update(['status' => PostStatus::Locked]);

        $updater->dispatch();
    }

    public function unlockRelative()
    {
        $updater = new UpdateManyPostJob(function () {
            return Post::whereIn('id', $this->getRelativePostId())
                ->whereDoesntHave('blacklist')
                ->whereStatus(PostStatus::Locked);
        });

        $updater->update(['status' => PostStatus::Published]);

        $updater->dispatch();
    }

    public function filterKeywords(Collection $posts)
    {
        $keywords = Keyword::all()->filter(fn(Keyword $key) => $key->isNot($this));

        return $keywords->reduce(function (Collection $carry, Keyword $key) {
            return $carry->diff($key->posts)->values();
        }, $posts);
    }

    public function getRelativePostId()
    {
        return Post::whereIn('phone', $this->getPhones())
            ->whereNotIn('id', $this->posts)
            ->get(['id'])
            ->keyBy('id')
            ->keys();
    }

    public function getPhones()
    {
        return Post::whereIn('id', $this->posts)
            ->get(['phone'])
            ->whereNotNull('phone')
            ->keyBy('phone')
            ->keys()
            ->unique();
    }

    private function makeUnicodeRegex()
    {
        $characters = collect(
            preg_split('//u', preg_quote($this->key, '/'))
        );

        return $characters->map(function ($char) {
            $ascii = Str::ascii($char);

            if (mb_strlen($ascii) === 0) {
                return $char;
            }

            return Str::isAscii($char) ? "$char" : "($char|$ascii)";
        })->join('');
    }
}
