<?php

namespace App\Jobs\Post;

use Carbon\Carbon;
use App\Enums\PostType;
use App\Repository\Post;
use App\Enums\PostSource;
use App\Enums\PostStatus;
use Illuminate\Support\Str;
use App\Services\System\Post\Fee;
use App\Repository\Location\District;
use App\Repository\Location\Province;

class ImportNguonChinhChuComJob extends ImportTccJob
{
    protected $mapped = [
        'bán nhà mặt phố' => 'Bán nhà mặt phố',
        'bán nhà trong ngõ' => 'Bán nhà nhà riêng, trong ngõ',
        'bán căn hộ, chung cư' => 'Bán căn hộ, chung cư',
        'bán biệt thự, liền kề' => 'Bán biệt thự, liền kề, phân lô',
        'bán nhà tập thể' => 'Bán nhà tập thể',
        'bán đất' => 'Bán đất ở, đất thổ cư',
        'bán kho, xưởng' => 'Bán kho, xưởng',

        'cho thuê nhà mặt phố' => 'Cho thuê nhà mặt phố',
        'cho thuê nhà trong ngõ' => 'Cho thuê nhà riêng, trong ngõ',
        'cho thuê căn hộ, chung cư' => 'Cho thuê căn hộ, chung cư',
        'cho thuê biệt thự, liền kề' => 'Cho thuê biệt thự, liền kề, phân lô',
        'cho thuê nhà tập thể' => 'Cho thuê nhà tập thể',
        'cho thuê kho, xưởng' => 'Cho thuê kho, xưởng',
        'cho thuê cửa hàng - kiot' => 'Cho thuê văn phòng, mặt bằng kinh doanh',
        'cho thuê văn phòng, mbkd' => 'Cho thuê văn phòng, mặt bằng kinh doanh',
    ];


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Post $post)
    {
        $this->setReport((array) $this->post);
        $this->post->category = $this->mapped[Str::lower($this->post->category)] ?? $this->post->category;

        if ($post->where('hash', $this->post->hash)->exists()) {
            return;
        }

        $date = array_reverse(explode('-', $this->post->createDate));

        $category = $this->getCategory();
        $province = Province::where('name', 'regexp', $this->post->province)->first();
        $district = District::where('name', 'regexp', $this->post->district)->first();

        if (! $province && $district && $district->province) {
            $province = $district->province;
        }

        if ($category[0] === null) {
            throw new \Exception('Category not found');
        }

        Fee::create([
            'title'       => $this->post->title,
            'content'     => nl2br($this->post->content),
            'hash'        => $this->post->hash,
            'publish_at'  => Carbon::createFromDate(...$date),
            'status'      => PostStatus::Published,
            'type'        => PostType::PostFee,
            'source'      => PostSource::NguonChinhChuCom,
            'categories'  => $category,
            'price'       => $this->post->price,
            'phone'       => $this->post->phone,
            'province_id' => $province->id ?? null,
            'district_id' => $district->id ?? null,
            'extra'       => [
                'url' => $this->post->url ?? null,
            ]
        ])->searchable();
    }
}
