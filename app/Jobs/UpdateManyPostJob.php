<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateManyPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 360;

    protected $builder;

    protected array $attributes;

    protected bool $force = false;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($builder)
    {
        $this->builder = value($builder);
    }

    public function update(array $attr)
    {
        $this->force = false;

        $this->attributes = $attr;
    }

    public function forceUpdate(array $attr)
    {
        $this->update($attr);

        $this->force = true;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->builder->chunk(2000, function ($posts) {
            foreach ($posts as $post) {
                $this->force ?
                    $post->forceFill($this->attributes)->save() :
                    $post->fill($this->attributes)->save();
            }
        });
    }
}
