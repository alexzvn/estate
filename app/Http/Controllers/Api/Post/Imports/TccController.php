<?php

namespace App\Http\Controllers\Api\Post\Imports;

use App\Http\Controllers\Api\Post\ImportController;
use App\Jobs\Post\ImportTccJob;
use Illuminate\Support\Collection;

class TccController extends ImportController
{
    public function queue(Collection $posts)
    {
        $posts->each(function ($post) {
            $post->hash  = sha1($post->url);

            $phone = $this->normalizePhone($post->phone);

            if ($phone != trim($post->phone)) {
                $post->content .= "\n $post->phone";
            }

            $post->phone = $phone;

            $post->price = $this->normalizePrice($post->price);

            ImportTccJob::dispatch($post);
        });
    }
}
