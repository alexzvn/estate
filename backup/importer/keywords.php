<?php

$post = function ($posts) {
    $posts = collect($posts ?? [])->map(
        fn($oid) => id('posts', $oid)
    );

    return (string) $posts;
};

return get('keywords', new Mapper([
    'key' => 'string',
    'linear' => 'boolean',
    'count' => 'int',
    'relative' => 'int',
    'posts' => $posts,
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
]));
