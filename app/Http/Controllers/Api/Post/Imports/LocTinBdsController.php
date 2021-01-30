<?php

namespace App\Http\Controllers\Api\Post\Imports;

use App\Enums\PostSource;
use App\Models\Category;
use App\Enums\PostStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Location\District;
use App\Models\Location\Province;
use Illuminate\Support\Collection;
use App\Http\Controllers\Api\Post\ImportController;
use App\Jobs\Post\ImportLocTinBdsJob;

class LocTinBdsController extends ImportController
{
    public function queue(Collection $posts)
    {
        $this->queueFacebook($posts->filter(function ($post)
        {
            return $post->source === 'NGUỒN FACEBOOK';
        }));

        $this->queueWebPosts($posts->filter(function ($post)
        {
            return $post->source === 'NGUỒN WEB BĐS';
        }));
    }

    public function queueWebPosts(Collection $posts)
    {
        $posts->each(function ($post)
        {
            $info = (object) [
                'title'        => $post->title,
                'content'      => nl2br($post->content),
                'price'        => $this->normalizePrice($post->price),
                'phone'        => $this->getPhone($post->phoneNumber, $post->title, $post->content),
                'publish_at'   => $this->getDate($post->createdDate),
                'province_id'  => $this->getProvince($post->city)->id ?? null,
                'district_id'  => $this->getDistrict($post->district)->id ?? null,
                'categories'   => $this->getWebCategories($post->type),
                'hash'         => "loctinbds.web.$post->id",
                'source'       => PostSource::LocTinBds,
                'extra'    => (object) [
                    'source'      => $post->source,
                    'authorName'  => $post->fbPostAuthorName,
                    'authorUrl'   => $post->fbPostAuthorUrl,
                    'originalUrl' => $post->seeMoreUrl ?? null
                ]
            ];

            $info->status = $this->getStatus($post->type, $info->phone);

            ImportLocTinBdsJob::dispatch($info);
        });
    }

    public function queueFacebook(Collection $posts)
    {
        $posts->each(function ($post)
        {
            $info = (object) [
                'title'        => $post->title,
                'content'      => nl2br($post->content),
                'price'        => $this->normalizePrice($post->price),
                'phone'        => $this->getPhone($post->phoneNumber, $post->title, $post->content),
                'publish_at'   => $this->getDate($post->createdDate),
                'province_id'  => $this->getProvince($post->city)->id ?? null,
                'district_id'  => $this->getDistrict($post->district)->id ?? null,
                'categories'   => $this->getFacebookCategories($post->type),
                'hash'         => "loctinbds.facebook.$post->id",
                'extra'    => (object) [
                    'source'      => $post->source,
                    'groupName'   => $post->fbGroupName,
                    'groupUrl'    => $post->fbGroupUrl,
                    'authorName'  => $post->fbPostAuthorName,
                    'authorUrl'   => $post->fbPostAuthorUrl,
                    'originalUrl' => $post->originalUrl
                ]
            ];

            $info->status = $this->getStatus($post->type, $info->phone);

            ImportLocTinBdsJob::dispatch($info);
        });
    }

    private function getStatus($type, $phone)
    {
        if (
            empty($phone) &&
            ! Str::contains(Str::lower($type), 'cần tìm')
        ) {
            return PostStatus::Draft;
        }

        return PostStatus::Published;
    }

    private function getWebCategories($type)
    {
        static $categories;

        $type = $this->mapWebCategories($type);

        if (empty($categories)) {
            $categories = Category::childrenOnly()->get();
        }

        return [
            $categories->filter(function ($category) use ($type) {
                return preg_match("/$type/", $category->name);
            })->first()
        ];
    }

    private function mapWebCategories($type)
    {
        $mapped = [
            'bán nhà'      => 'Bán nhà nhà riêng, trong ngõ',
            'bán đất'      => 'Bán đất ở, đất thổ cư',
            'bán chung cư' => 'Bán căn hộ, chung cư',
            'sang nhượng'  => 'Cho thuê văn phòng, mặt bằng kinh doanh'
        ];

        return $mapped[Str::lower($type)] ?? 'Cho thuê khác';
    }

    private function getFacebookCategories($type)
    {
        static $categories;

        if (! isset($categories)) {
            $categories = [
                Category::childrenOnly()->where('name', 'regexp', '/bán facebook/')->first(),
                Category::childrenOnly()->where('name', 'regexp', '/thuê facebook/')->first(),
                Category::childrenOnly()->where('name', 'regexp', '/Khách cần mua & thuê/')->first(),
            ];
        }

        [$sell, $rent, $needed] = $categories;

        if (Str::contains(Str::lower($type), 'cần tìm')) {
            return [$needed];
        }

        return Str::contains($type, ['Cho Thuê', 'Sang Nhượng']) ? [$rent]: [$sell];
    }

    private function getPhone(...$content)
    {
        foreach ($content as $value) {
            if ($this->normalizePhone($value)) {
                return $this->normalizePhone($value);
            }
        }

        return null;
    }

    private function getDate(string $date)
    {
        return Carbon::createFromFormat('d/m/y', $date);
    }
}
