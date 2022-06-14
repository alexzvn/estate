<?php

namespace App\Http\Controllers\Api\Post\Imports;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Jobs\Post\ImportNguonChinhChuComJob;
use App\Http\Controllers\Api\Post\ImportController;

class NguonChinhChuController extends ImportController
{
    public function queue(Collection $posts)
    {
        $posts->each(function ($post) {
            parse_str(parse_url($post->url, PHP_URL_QUERY), $query);

            $hash = 'nguonchinhchu.com.' . $query['id'];
            $phone = $this->normalizePhone($post->phone);

            $post->hash = $hash;
            $post->phone = $phone;
            $post->price = $this->normalizePrice($post->price);

            ImportNguonChinhChuComJob::dispatch($post);
        });
    }
}
