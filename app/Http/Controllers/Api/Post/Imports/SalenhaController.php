<?php

namespace App\Http\Controllers\Api\Post\Imports;

use App\Http\Controllers\Api\Post\ImportController;
use App\Jobs\Post\ImportSalenhaJob;
use Illuminate\Support\Collection;

class SalenhaController extends ImportController
{
    public function queue(Collection $posts)
    {
        $posts->each(function ($post) {
           $hash = sha1($post->url);
           $phone = $this->normalizePhone($post->phone);

           $post->hash = $hash;
           $post->phone = $phone;
           $post->price = $this->normalizePrice($post->price);

           ImportSalenhaJob::dispatch($post);
        });
    }
}
