<?php

namespace App\Services\System\Post;

use App\Models\Post;
use App\Repository\Category;
use App\Services\System\Post\Handler\CastPriceToInt;
use App\Services\System\Post\Handler\CleanScript;
use App\Services\System\Post\Handler\MakeCategories;
use App\Services\System\Post\Handler\MakeDistrict;
use App\Services\System\Post\Handler\MakeProvince;
use Illuminate\Pipeline\Pipeline;

trait PostService
{
    protected static $fillable = [
        'content',
        'title',
        'type',
        'status',
        'phone',
        'price',
        'hash',
        'commission',
        'province_id',
        'district_id',
        'publish_at'
    ];

    /**
     * Create post
     *
     * @param array $attr
     * @return \App\Models\Post
     */
    public static function create(array $attr)
    {
        $attr = collect(self::handleRawAttribute($attr));

        $post = Post::forceCreate(
            $attr->only(static::$fillable)->toArray()
        );

        $post->categories()->sync($attr['categories']);

        return $post;
    }

    public static function update(Post $post, array $attr)
    {
        $attr = collect(self::handleRawAttribute($attr));

        $post->update(
            $attr->only(static::$fillable)->toArray()
        );

        $post->categories()->sync($attr['categories']);

        return $post;
    }

    /**
     * Handle raw attribute
     *
     * @param array $attr
     * @return array
     */
    private static function handleRawAttribute(array $attr)
    {
        return (new Pipeline(app()))
            ->send((object) $attr)
            ->through([
                CleanScript::class,
                CastPriceToInt::class,
                MakeCategories::class,
                MakeDistrict::class,
                MakeProvince::class
            ])
            ->via('handle')
            ->then(function ($attr) {
                return (array) $attr;
            });
    }

    public static function deleteMany(array $ids)
    {
        return (new static)
            ->whereIn('_id', $ids)
            ->get()
            ->each(function ($post) {
                $post->delete();
            })->count();
    }

    public static function reverseMany(array $ids)
    {
        return (new static)
            ->whereIn('_id', $ids)
            ->get()
            ->each(function ($post) {
                $post->forceFill(['publish_at' => now(), 'reverser' => true])->save();
            })->count();
    }
}
