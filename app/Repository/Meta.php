<?php

namespace App\Repository;

use App\Models\PostMeta;

class Meta extends BaseRepository
{
    public function __construct(PostMeta $model) {
        $this->model = $model;
    }

    /**
     * Create and fill meta by name, value
     *
     * @param string $name
     * @param mixed $value
     * @return \App\Models\PostMeta
     */
    public static function from(string $name, $value = null)
    {
        return new PostMeta([
            'name' => $name,
            'value' => $value
        ]);
    }

    /**
     * Create and fill many meta
     *
     * @param array $nameValues [name => value]
     * @return \App\Models\PostMeta[]
     */
    public static function fromMany(array $nameValues)
    {
        $meta = collect();

        foreach ($nameValues as $name => $value) {
            $meta->push(self::from($name, $value));
        }

        return $meta;
    }
}
