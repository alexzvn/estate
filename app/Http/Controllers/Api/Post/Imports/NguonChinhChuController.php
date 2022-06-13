<?php

namespace App\Http\Controllers\Api\Post\Imports;

use App\Http\Controllers\Api\Post\ImportController;
use App\Jobs\Post\ImportNguonChinhChuComJob;
use Illuminate\Support\Collection;

class NguonChinhChuController extends ImportController
{
    public function queue(Collection $posts)
    {
        $posts->each(function ($post) {
           $hash = sha1($post->url);
           $phone = $this->normalizePhone($post->phone);

           $post->hash = $hash;
           $post->phone = $phone;
           $post->price = $this->normalizePrice($post->price);

           ImportNguonChinhChuComJob::dispatch($post);
        });
    }
}
