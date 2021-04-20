<?php

namespace App\Helpers;

final class Updater
{
    protected $builder;

    public function __construct($builder) {
        $this->builder = $builder;
    }

    public function update(array $attributes)
    {
        return $this->builder->chunk(2000, function ($posts) use ($attributes) {
            $posts->each(function ($post) use ($attributes) {
                $post->fill($attributes)->save();
            });
        });
    }

    public function forceUpdate(array $attributes)
    {
        return $this->builder->chunk(2000, function ($posts) use ($attributes) {
            $posts->each(function ($post) use ($attributes) {
                $post->forceFill($attributes)->save();
            });
        });
    }
}
