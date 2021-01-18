<?php

namespace App\Http\Controllers\Api\Post;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Repository\Location\District;
use App\Repository\Location\Province;

abstract class ImportController extends Controller
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

    protected function normalizePhone($content)
    {
        $vietnamPhone = '/(\+?84|0)(\s|\.)?(\d{1,4})(\s|\.)?(\d{2,4})(\s|\.)?(\d{2,5})/im';

        if (preg_match($vietnamPhone, $content, $matches)) {
            return "0{$matches[3]}{$matches[5]}{$matches[7]}";
        }

        return null;
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

    protected function getProvince(string $name)
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

    protected function getDistrict(string $name)
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

    abstract public function queue(Collection $posts);
}
