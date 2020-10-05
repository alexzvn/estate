<?php

namespace App\Http\Controllers\Api\Post;

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

        if ($posts->isEmpty()) {
            return response([
                'message' => 'the post was empty',
                'success' => false
            ], 400);
        }

        $this->queue($posts);

        return ['message' => 'okey', 'success' => true];
    }

    protected function queue($posts)
    {
        $posts->each(function ($post) {
            $post->hash  = sha1($post->url);

            $price = $this->stringPriceToNumber($post->price ?? '');

            $post->price = $price || $price == 0 ? round($price) : null;

            ImportPost::dispatch($post);
        });
    }

    protected function stringPriceToNumber(string $price)
    {
        if (preg_match('/^[0-9]+$/', $price)) {
            return (float) $price;
        }

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
