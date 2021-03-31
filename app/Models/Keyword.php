<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Support\Facades\Bus;

class Keyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'count',
        'posts'
    ];

    protected $casts = [
        'posts' => AsCollection::class
    ];

    public function toRegex()
    {
        $keyword = preg_quote($this->key);

        return "/(?:^|\W)$keyword(?:$|\W)/i";
    }

    public function lock()
    {
        Post::whereIn('_id', $this->posts)->update([
            'status' => PostStatus::Locked
        ]);
    }

    public function unlock()
    {
        Post::whereIn('_id', $this->posts)->update([
            'status' => PostStatus::Published
        ]);
    }

    public function index()
    {
        $indexer = function () {
            $posts = Post::where('content', 'regexp', $this->toRegex())->get();

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
        if (preg_match($this->toRegex(), $post->content)) {
            $posts = $this->posts->push($post->id);
        }

        $posts = $posts->unique();

        return $this->fill([
            'count' => $posts->count(),
            'posts' => $posts
        ])->save();
    }
}
