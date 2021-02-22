<?php

namespace App\Jobs\Post;

use App\Enums\PostSource;
use Carbon\Carbon;
use App\Repository\Post;
use App\Enums\PostStatus;
use App\Enums\PostType;
use Illuminate\Support\Str;
use App\Repository\Category;
use App\Repository\Location\District;
use App\Repository\Location\Province;
use App\Services\System\Post\Online;

class ImportTccJob extends ImportPostJob
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

        $date = array_reverse(explode('/', $this->post->createDate));

        $category = $this->getCategory();
        $province = Province::where('name', 'regexp', "/{$this->post->province}/")->first();
        $district = District::where('name', 'regexp', "/{$this->post->district}/")->first();

        if ($this->checkItems($province, $district, $category)) {
            $this->setReport((array) $this->post);
            throw new \Exception("Province, district or category is empty", 1);
        }

        $post = Online::create([
            'title'       => $this->post->title,
            'content'     => nl2br($this->post->content),
            'hash'        => $this->post->hash,
            'publish_at'  => Carbon::createFromDate(...$date),
            'status'      => PostStatus::Published,
            'type'        => PostType::Online,
            'source'      => PostSource::TinChinhChu,
            'categories'  => $category,
            'price'       => $this->post->price,
            'phone'       => $this->post->phone,
            'province_id' => $province->id ?? null,
            'district_id' => $district->id ?? null,
            'extra'       => ['url' => $this->post->url ?? null]
        ]);

        if ($this->isInBlacklist($post->phone)) {
            $post->fill(['status' => PostStatus::Locked]);
        }

        $post->save();
    }

    protected function checkItems(...$items)
    {
        foreach ($items as $item) {
            if (empty($item)) return true;
        }

        return false;
    }

    protected function setReport(array $context)
    {
        \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($context) {
            foreach ($context as $key => $value) {
                $scope->setExtra("post.$key", $value);
            }
        });
    }

    protected function getCategory()
    {
        $category = ucfirst(Str::lower($this->post->category));
        $category = trim($category);

        $category = Category::where('name', 'like', "%$category%")->first();

        return [$category];
    }
}
