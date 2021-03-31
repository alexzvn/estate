<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Bus;
use Jenssegers\Mongodb\Eloquent\Model;
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
        'posts'
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

    public function lock()
    {
        Post::whereIn('_id', $this->posts)
            ->whereStatus(PostStatus::Published)
            ->update([
                'status' => PostStatus::Locked
            ]);
    }

    public function unlock()
    {
        $keywords = Keyword::all()->filter(fn(Keyword $key) => $key->isNot($this));

        // remove duplicated id from another 
        $posts = $keywords->reduce(function (Collection $carry, Keyword $key) {
            return $carry->diff($key->posts)->values();
        }, $this->posts);

        Post::whereStatus(PostStatus::Locked)
            ->whereIn('_id', $posts)
            ->update([
                'status' => PostStatus::Published
            ]);
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
        $indexer = function () {
            $posts = Post::where('content', 'regexp', $this->toRegex())
                ->orWhere('title', 'regexp', $this->toRegex())->get();

            $this->fill([
                'count' => $posts->count(),
                'posts' => $posts->map(fn($post) => $post->id)
            ])->save();
        };

        return Bus::chain([
            $indexer,
            fn() => $this->refresh()->lock()
        ])->dispatch();
    }

    public function indexPost(Post $post)
    {
        $content = "$post->title $post->content";

        if ($this->test($content)) {
            $post->lock();
            $this->posts = $this->posts->push($post->id);

        } elseif ($post->isLocked()) {
            $post->publish();
        }

        return $this->fill([
            'count' => $this->posts->count(),
            'posts' => $this->posts
        ])->save();
    }

    private function makeUnicodeRegex()
    {
        $characters = collect(
            preg_split('//u', preg_quote($this->key))
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
