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

class ImportSalenhaJob extends ImportTccJob
{
    protected $mapped = [
        'bán đất' => 'Bán đất ở, đất thổ cư',
        'nhà trong ngõ' => 'Bán nhà nhà riêng, trong ngõ',
        'bán chung cư' => 'Bán căn hộ, chung cư',
        'nhà mặt phố' => 'Bán nhà mặt phố',

        'thuê nhà mặt phố' => 'Cho thuê nhà mặt phố',
        'thuê nhà trong ngõ' => 'Cho thuê nhà riêng, trong ngõ',
        'thuê chung cư' => 'Cho thuê căn hộ, chung cư',
    ];

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Post $post)
    {
        $this->setReport((array) $this->post);

        $this->post->category = $this->mapped[Str::lower(trim($this->post->category))] ?? $this->post->category;

        if ($post->where('hash', $this->post->hash)->exists()) {
            return;
        }

        $date = array_reverse(explode('-', $this->post->createDate));

        $category = $this->getCategory();
        $province = Province::where('name', 'regexp', $this->post->province)->first();
        $district = District::where('name', 'regexp', $this->post->district)->first();

        if ($category[0] === null) {
            throw new \Exception('Category not found');
        }

        if (! $province && $district && $district->province) {
            $province = $district->province;
        }

        Fee::create([
            'title'       => $this->post->title,
            'content'     => nl2br($this->post->content . "\n Địa chỉ: " . $this->post->address),
            'hash'        => $this->post->hash,
            'publish_at'  => Carbon::createFromDate(...$date),
            'status'      => PostStatus::Published,
            'type'        => PostType::PostFee,
            'source'      => PostSource::Salenha,
            'categories'  => $category,
            'price'       => $this->post->price,
            'phone'       => $this->post->phone,
            'province_id' => $province->id ?? null,
            'district_id' => $district->id ?? null,
            'extra'       => [
                'url' => $this->post->url ?? null,
                'area' => $this->post->area ?? null,
            ]
        ])->searchable();
    }
}
