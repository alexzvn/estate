<?php

namespace App\Http\Controllers\Api\Post\Imports;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Jobs\Post\ImportNguonChinhChuComJob;
use App\Http\Controllers\Api\Post\ImportController;

class NguonChinhChuController extends ImportController
{
    protected $mapped = [
        'bán nhà mặt phố' => 'Bán nhà mặt phố',
        'bán nhà trong ngõ' => 'Bán nhà riêng, trong ngõ',
        'bán căn hộ, chung cư' => 'Bán căn hộ, chung cư',
        'bán biệt thự, liền kề' => 'Bán biệt thự, liền kề, phân lô',
        'bán nhà tập thể' => 'Bán nhà tập thể',
        'bán đất' => 'Bán đất ở, đất thổ cư',
        'bán kho, xưởng' => 'Bán kho, xưởng',

        'cho thuê nhà mặt phố' => 'Cho thuê nhà mặt phố',
        'cho thuê nhà trong ngõ' => 'Cho thuê nhà riêng, trong ngõ',
        'cho thuê căn hộ, chung cư' => 'Cho thuê căn hộ, chung cư',
        'cho thuê biệt thự, liền kề' => 'Cho thuê biệt thự, liền kề, phân lô',
        'cho thuê nhà tập thể' => 'Cho thuê nhà tập thể',
        'cho thuê kho, xưởng' => 'Cho thuê kho, xưởng',
        'cho thuê cửa hàng - kiot' => 'Cho thuê văn phòng, mặt bằng kinh doanh',
        'cho thuê văn phòng, mbkd' => 'Cho thuê văn phòng, mặt bằng kinh doanh',
    ];

    public function queue(Collection $posts)
    {
        $posts->each(function ($post) {
           $hash = sha1($post->url);
           $phone = $this->normalizePhone($post->phone);

           $post->category = $this->mapped[Str::lower($post->category)] ?? $post->category;
           $post->hash = $hash;
           $post->phone = $phone;
           $post->price = $this->normalizePrice($post->price);

           ImportNguonChinhChuComJob::dispatch($post);
        });
    }
}
