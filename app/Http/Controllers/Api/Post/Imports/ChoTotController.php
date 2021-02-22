<?php

namespace App\Http\Controllers\Api\Post\Imports;

use App\Enums\PostSource;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Jobs\Post\ImportChoTotJob;
use Illuminate\Support\Collection;
use App\Http\Controllers\Api\Post\ImportController;

class ChoTotController extends ImportController
{
    protected $mapped = [
        'mua bán căn hộ/chung cư'                 => 'Bán căn hộ, chung cư',
        'mua bán nhà ở'                           => 'Bán nhà nhà riêng, trong ngõ',
        'mua bán đất'                             => 'Bán đất ở, đất thổ cư',
        'mua bán văn phòng, mặt bằng kinh doanh'  => 'Bán văn phòng, mặt bằng kinh doanh',
        'cho thuê căn hộ/chung cư'                => 'Cho thuê căn hộ, chung cư',
        'cho thuê nhà ở'                          => 'Cho thuê nhà riêng, trong ngõ',
        'cho thuê đất'                            => 'Bán đất ở, đất thổ cư',
        'cho thuê văn phòng, mặt bằng kinh doanh' => 'Cho thuê văn phòng, mặt bằng kinh doanh',
        'cho thuê phòng trọ'                      => 'Cho thuê khác',
    ];

    public function queue(Collection $posts)
    {
        $posts->each(function ($raw)
        {
            [$province, $district] = array_reverse(explode(',', $raw->address));

            $post = [
                'title'       => $raw->title,
                'content'     => "$raw->content \n Địa chỉ: $raw->address",
                'price'       => $this->normalizePrice($raw->price),
                'phone'       => $this->normalizePhone($raw->phoneNumber, $raw->content),
                'province_id' => $this->findProvince($province)->id ?? null,
                'district_id' => $this->findDistrict($district)->id ?? null,
                'categories'  => $this->findCategories(Str::lower($raw->category)),
                'hash'        => sha1($raw->url),
                'source'      => PostSource::ChoTot,
                'extra'       => (object) $this->makeExtra($raw)
            ];

            ImportChoTotJob::dispatch($post);
        });
    }

    public function makeExtra($raw)
    {
        return collect((array) $raw)->only([
            'priceM2',
            'rooms',
            'direction',
            'toilets',
            'propertyLegalDocument',
            'propertyBackCondition',
            'houseType',
            'furnishingSell',
            'width',
            'length',
            'livingSize',
            'acreage',
            'url'
        ])->toArray();
    }

    protected function findCategories($name)
    {
        if (! (isset($this->mapped[$name]) && $type = $this->mapped[$name])) {
            return [];
        }

        $category = Category::where('name', 'regexp', "/$type/")->first();

        return $category ? [$category] : [];
    }

    private function findProvince($name)
    {
        $name = preg_replace('/^Tp/', '', trim($name));

        return $this->getProvince($name);
    }

    private function findDistrict($name)
    {
        return $this->getDistrict(trim($name));
    }
}
