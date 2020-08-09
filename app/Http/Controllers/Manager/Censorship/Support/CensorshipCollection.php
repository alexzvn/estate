<?php

namespace App\Http\Controllers\Manager\Censorship\Support;

class CensorshipCollection extends \Illuminate\Support\Collection
{
    public function shouldWarning(string $phone)
    {
        return (
            $this->getProvinceByPhone($phone)->count() >= 3 ||
            $this->getCategoriesByPhone($phone)->count() >= 3 ||
            $this->get($phone, collect())->count() >= 3
        );
    }

    public function getProvinceByPhone(string $phone)
    {
        $meta = $this->get($phone, collect());

        $meta = $meta->map(function ($meta)
        {
            if ($meta->post) {
                $meta->post->loadMeta();
            }

            return $meta->post->meta->province ?? null;
        });

        return $meta->filter(function ($province) {
            return $province !== null;
        })->unique('_id');
    }

    public function getCategoriesByPhone(string $phone)
    {
        $meta = $this->get($phone, collect());

        $meta = $meta->map(function ($meta)
        {
            return $meta->post->categories[0] ?? null;
        });

        return $meta->filter(function ($categories) {
            return $categories !== null;
        })->unique('_id');
    }
}
