<?php

namespace App\Jobs\Post;

use App\Enums\PostSource;
use App\Enums\PostStatus;
use App\Enums\PostType;
use App\Repository\Location\District;
use App\Repository\Location\Province;
use App\Repository\Post;
use App\Services\System\Post\Fee;
use Carbon\Carbon;

class ImportSalenhaJob extends ImportTccJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Post $post)
    {
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
