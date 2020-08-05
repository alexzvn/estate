<?php

namespace App\Http\Controllers\Api\Post;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Post\ImportPostJob as ImportPost;

class ImportController extends Controller
{
    protected $price = [
        'tỷ'    => 1000000000,
        'triệu' => 1000000,
        'nghìn' => 1000,
        'trăm'  => 100,
        'chục'  => 10,
    ];

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $posts = collect(json_decode($request->getContent()));

        $posts->map(function ($post) {
            $post->hash  = sha1($post->url);

            $price = $this->stringPriceToNumber($post->price);

            $post->price = $price ? round($price) : null;

            ImportPost::dispatch($post)->afterResponse();

            return $post;
        });

        return $posts;
    }

    protected function stringPriceToNumber(string $price)
    {
        if (count(explode(' ', $price)) < 2) {
            return null;
        }

        [$price, $priceString] = explode(' ', $price);

        $priceString = (int) str_replace(
            array_keys($this->price),
            array_values($this->price),
            $priceString
        );

        return ((float) $price) * $priceString;
    }
}
