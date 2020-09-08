<?php

namespace App\Services\System\Post;

use App\Models\Post;
use App\Services\System\Post\Handler\CastPriceToInt;
use App\Services\System\Post\Handler\CleanScript;
use App\Services\System\Post\Handler\MakeCategories;
use App\Services\System\Post\Handler\MakeDistrict;
use App\Services\System\Post\Handler\MakeProvince;
use Illuminate\Pipeline\Pipeline;

trait PostService
{
    public static function create(array $attr)
    {
        $attr = collect(self::handleRawAttribute($attr));

        return Post::forceCreate($attr->only(
            'content',
            'title',
            'type',
            'status',
            'phone',
            'price',
            'commission',
            'province_id',
            'district_id',
            'category_ids',
            'publish_at'
        ));
    }

    /**
     * Handle raw attribute
     *
     * @param array $attr
     * @return array
     */
    private static function handleRawAttribute(array $attr)
    {
        return (new Pipeline())
            ->send((object) $attr)
            ->through([
                CleanScript::class,
                CastPriceToInt::class,
                MakeCategories::class,
                MakeDistrict::class,
                MakeProvince::class
            ])
            ->then(function ($attr) {
                return (array) $attr;
            });
    }
}
