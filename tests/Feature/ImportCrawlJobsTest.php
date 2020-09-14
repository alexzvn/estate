<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Enums\PostType;
use App\Models\Category;
use App\Enums\PostStatus;
use Illuminate\Support\Carbon;
use App\Jobs\Post\ImportPostJob;
use App\Services\System\Post\Online;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestImportCrawlJobs extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_import_crawl_jobs_tcc()
    {
        $this->expectsJobs(ImportPostJob::class);

        $exampleData = file_get_contents(base_path('example.json'));

        $response = $this->postJson(
            route('api.crawl.import.tcc'),
            json_decode($exampleData, true)
        );

        $response->assertStatus(200);
    }

    public function test_model_post()
    {
        $exampleData = $this->getExampleData();

        $model = Online::create($exampleData);

        foreach (['content', 'categories', 'publish_at'] as $key) {
            unset($exampleData[$key]);
        }

        foreach ($exampleData as $key => $value) {
            $this->assertSame($value, $model->{$key}, $key);
        }
    }

    protected function getExampleData()
    {
        return [
            'title'       => 'this is title',
            'content'     => nl2br("some \n content \n for test"),
            'hash'        => md5('abc'),
            'publish_at'  => Carbon::createFromDate(2020, 9, 14),
            'status'      => PostStatus::Published,
            'type'        => PostType::Online,
            'categories'  => [Category::first()],
            'price'       => 100000000,
            'phone'       => '0123456789',
            'province_id' => 'province_id',
            'district_id' => 'district_id',
        ];
    }
}
