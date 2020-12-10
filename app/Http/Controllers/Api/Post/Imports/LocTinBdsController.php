<?php

namespace App\Http\Controllers\Api\Post\Imports;

use App\Enums\PostStatus;
use App\Http\Controllers\Api\Post\ImportController;
use App\Jobs\Post\ImportFacebookJob;
use App\Models\Category;
use App\Models\Location\District;
use App\Models\Location\Province;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class LocTinBdsController extends ImportController
{
    public function queue(Collection $posts)
    {
        $this->queueFacebook($posts->filter(function ($post)
        {
            return $post->source === 'NGUỒN FACEBOOK';
        }));
    }

    public function queueFacebook(Collection $posts)
    {
        [$sell, $rent] = $this->getFacebookCategories();

        $posts->each(function ($post) use ($sell, $rent)
        {
            ImportFacebookJob::dispatch((object) [
                'title'        => $post->title,
                'content'      => nl2br($post->content),
                'price'        => $this->normalizePrice($post->price),
                'phone'        => $this->normalizePhone($post->phoneNumber),
                'status'       => PostStatus::Published,
                'publish_at'   => $this->getDate($post->createdDate),
                'province_id'  => $this->getProvince($post->city)->id ?? null,
                'district_id'  => $this->getDistrict($post->district)->id ?? null,
                'categories'   => $post->type === 'Cần Thuê' ? [$rent] : [$sell],
                'hash'         => sha1($post->originalUrl),
                'extra'    => (object) [
                    'source'     => $post->source,
                    'groupName'  => $post->fbGroupName,
                    'groupUrl'   => $post->fbGroupUrl,
                    'authorName' => $post->fbPostAuthorName,
                    'authorUrl'  => $post->fbPostAuthorUrl,
                ]
            ]);
        });
    }

    private function getDate(string $date)
    {
        return Carbon::createFromFormat('d/m/y', $date);
    }

    private function getProvince(string $name)
    {
        static $provinces;

        if ($provinces === null) {
            $provinces = Province::all();
        }

        return $provinces->filter(function ($province) use ($name)
        {
            return preg_match("/$name/", $province->name);
        })->first();
    }

    private function getDistrict(string $name)
    {
        static $districts;

        if ($districts === null) {
            $districts = District::all();
        }

        return $districts->filter(function ($district) use ($name)
        {
            return preg_match("/$name/", $district->name);
        })->first();
    }

    private function getFacebookCategories()
    {
        return [
            Category::childrenOnly()->where('name', 'regexp', '/bán facebook/')->first(),
            Category::childrenOnly()->where('name', 'regexp', '/thuê facebook/')->first(),
        ];
    }
}
