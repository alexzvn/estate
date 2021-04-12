<?php

$post = function ($posts = null) {
    $posts = collect($posts ?? [])->map(
        fn($oid) => id('posts', $oid)
    );

    return (string) $posts;
};

return get('keywords', new Mapper([
    'key'        => 'string',
    'linear'     => 'boolean',
    'count'      => 'int',
    'relative'   => 'int',
    'posts'      => $post,
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
]));
