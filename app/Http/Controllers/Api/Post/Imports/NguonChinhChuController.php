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
            preg_match('/id=([0-9]+)/', $post->url, $matches);

            $phone = $this->normalizePhone($post->phone);

            $post->hash  = 'nguonchinhchu.com.' . $matches[1];
            $post->phone = $phone;
            $post->price = $this->normalizePrice($post->price);

            ImportNguonChinhChuComJob::dispatch($post);
        });
    }
}
