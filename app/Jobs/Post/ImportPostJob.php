<?php

namespace App\Jobs\Post;


use stdClass;
use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Mews\Purifier\Facades\Purifier;

use App\Enums\PostMeta;
use App\Enums\PostStatus;
use App\Repository\Meta;
use App\Repository\Post;
use App\Repository\Category;
use App\Repository\Location\District;
use App\Repository\Location\Province;

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

        $post = $post->forceFill([
            'title'      => $this->post->title ?? '',
            'content'    => Purifier::clean($this->post->content) ?? '',
            'hash'       => $this->post->hash,
            'publish_at' => Carbon::createFromDate(...$date),
            'status'     => PostStatus::Pending
        ]);

        $post->content = nl2br($post->content);
        $post->save();

        $post->metas()->saveMany($this->makeMetas());

        if ($category = $this->getCategory()) {
            $post->categories()->save($category);
        }
    }

    protected function makeMetas()
    {
        $province = Province::where('name', 'regexp', "/{$this->post->province}/")->first();
        $district = District::where('name', 'regexp', "/{$this->post->district}/")->first();

        return Meta::fromMany([
            PostMeta::Province => $province->id ?? null,
            PostMeta::District => $district->id ?? null,
            PostMeta::Phone    => $this->post->phone ?? '',
            PostMeta::Price    => $this->post->price ?? null,
        ]);
    }

    protected function getCategory()
    {
        return Category::where('name', 'regexp', "/{$this->post->category}/")->first();
    }
}
