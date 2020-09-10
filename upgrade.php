<?php

use App\Models\Post;

$GLOBALS['total'] = Post::count();
$GLOBALS['count'] = 0;

Post::with(['metas', 'metas.province', 'metas.district'])->chunk(10000, function ($posts)
{
    $posts->each(function ($post)
    {
        $meta = $post->loadMeta()->meta;

        unset($post->meta);

        $post->forceFill([
            'phone' => $meta->phone->value ?? null,
            'commission' => $meta->commission->value ?? null,
            'district_id' => $meta->district->value ?? null,
            'province_id' => $meta->province->value ?? null,
            'price' => $meta->price->value ?? null,
        ])->save();

        $GLOBALS['count']++;

        echo "\r" . $GLOBALS['count'] . ' / ' . $GLOBALS['total'];
    });
});