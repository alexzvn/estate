<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;

class ImportController extends Controller
{
    private $price = [
        'tỷ'    => 1000000000,
        'triệu' => 1000000,
        'nghìn' => 1000,
        'trăm'  => 100,
        'chục'  => 10,
    ];

    /**
     * Body payload posts
     *
     * @var \Illuminate\Support\Collection
     */
    protected $posts;

    public function __construct() {
        $this->posts = collect(json_decode(request()->getContent()));
    }

    public function store()
    {
        if ($this->posts->isEmpty()) {
            return response([
                'message' => 'the post was empty',
                'success' => false
            ], 400);
        }

        $this->queue($this->posts);

        return ['message' => 'okey', 'success' => true];
    }

    protected function normalizePrice($price)
    {
        $price = $this->priceNumbered($price ?? '');

        return $price || $price == 0 ? round($price) : null;
    }

    private function priceNumbered($price)
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
