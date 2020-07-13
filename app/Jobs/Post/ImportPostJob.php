<?php

namespace App\Jobs\Post;

use App\Enums\PostMeta;
use App\Enums\PostStatus;
use App\Repository\Category;
use App\Repository\Location\District;
use App\Repository\Location\Province;
use App\Repository\Meta;
use App\Repository\Post;
use Carbon\Carbon;
use stdClass;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mews\Purifier\Facades\Purifier;

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
    public function handle(Post $model)
    {
        if ($model->where('hash', $this->post->hash)->exists()) {
            return;
        }

        [$day, $month, $year] = explode('/', $this->post->createDate);

        $model = $model->forceFill([
            'title'      => $this->post->title ?? '',
            'content'    => Purifier::clean($this->post->content) ?? '',
            'hash'       => $this->post->hash,
            'publish_at' => Carbon::createFromDate($year, $month, $day),
            'status'     => PostStatus::Pending
        ]);

        $model->save();

        $model->metas()->saveMany($this->makeMetas());

        if ($category = $this->getCategory()) {
            $model->categories()->save($category);
        }
    }

    protected function makeMetas()
    {
        $province = Province::where('name', 'regexp', "/{$this->post->province}/")->first();
        $district = District::where('name', 'regexp', "/{$this->post->district}/")->first();

        $province = $province ? $province->id : null;
        $district = $district ? $district->id : null;

        $meta = [
            PostMeta::Province => $province,
            PostMeta::District => $district,
            PostMeta::Phone    => $this->post->phone ?? '',
            PostMeta::Price    => $this->post->price ?? null,
        ];

        foreach ($meta as $name => $value) {
            $metas[] = Meta::fill([
                'name' => $name,
                'value' => $value
            ]);
        }

        return collect($metas ?? []);
    }

    protected function getCategory()
    {
        return Category::where('name', 'regexp', "/{$this->post->category}/")->first();
    }

    /**
     * Check if string a contains b and other wise
     * 
     * @return bool
     */
    protected function contains(string $a, string $b)
    {
        return Str::contains($a, $b) || Str::contains($b, $a);
    }
}
