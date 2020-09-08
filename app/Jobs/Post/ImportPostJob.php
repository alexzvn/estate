<?php

namespace App\Jobs\Post;


use stdClass;
use Carbon\Carbon;

use App\Repository\Post;
use App\Enums\PostStatus;
use App\Enums\PostType;
use App\Repository\Blacklist;
use Illuminate\Support\Str;

use App\Repository\Category;

use Illuminate\Bus\Queueable;
use App\Repository\Location\District;
use App\Repository\Location\Province;
use App\Services\System\Post\Online;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;

class ImportPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(stdClass $post)
    {
        $this->post = $post;
    }

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

        $province = Province::where('name', 'regexp', "/{$this->post->province}/")->first();
        $district = District::where('name', 'regexp', "/{$this->post->district}/")->first();

        $post = Online::create([
            'title'       => $this->post->title,
            'content'     => nl2br($this->post->content),
            'hash'        => $this->post->hash,
            'publish_at'  => Carbon::createFromDate(...$date),
            'status'      => PostStatus::Published,
            'type'        => PostType::Online,
            'categories'  => $this->getCategory(),
            'price'       => $this->post->price,
            'phone'       => $this->post->phone,
            'province_id' => $province->id ?? null,
            'district_id' => $district->id ?? null,
        ]);

        if ($this->getBlacklist()->where('phone', $this->post->phone)->isNotEmpty()) {
            $post->fill(['status' => PostStatus::Locked]);
        }

        $post->save();
    }


    protected function getCategory()
    {
        $category = ucfirst(Str::lower($this->post->category));
        $category = trim($category);

        $category = Category::where('name', 'like', "%$category%")->first();

        return [$category];
    }

    protected function getBlacklist()
    {
        return Cache::remember('blacklist.phone', now()->addMinute(), function ()
        {
            return Blacklist::all();
        });
    }
}
