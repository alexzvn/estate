<?php

return collect(get('categories'))

->reduce(function (array $carry, $cat)
{
    $catId = id('categories', $cat['_id']['$oid']);

    foreach ($cat['post_ids'] ?? [] as $oid) {

        if ($oid === null) continue;

        array_push($carry, [
            'post_id' => id('posts', $oid),
            'category_id' => $catId,
        ]);
    }

    return $carry;
}, []);