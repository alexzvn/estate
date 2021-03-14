<?php

$type = function ($key) {
    $mapped = [
        'Tin Xin Phí' => 1,
        'Tin Mua Bán - Thuê' => 2,
        'Tin Thị Trường' => 3,
        'Tin web online' => 4
    ];

    return $mapped[$key];
};

$types = function ($oid, $data) use ($type) {
    $types = array_map(function ($key) use ($type) {
        return $type[$key];
    }, $data['types'] ?? []);

    return json_encode($types);
};

$categories = function ($oid, $data) {
    $categories = collect($data['category_ids'] ?? [])
        ->map(fn($oid) => id('categories', $oid));

    return json_encode($categories->toArray());
};

$provinces = function ($oid, $data) {
    $provinces = collect($data['provinces_ids'] ?? [])
        ->map(fn($oid) => id('provinces', $oid));

    return json_encode($provinces->toArray());
};

return get('plans', new Mapper([
    'name' => 'string',
    'price' => 'int',
    'types' => $types,
    'categories' => $categories,
    'provinces'  => $provinces,
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
]));
