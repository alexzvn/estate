<?php

namespace App\Models;

use App\Enums\PostMeta;
use App\Repository\Meta;
use Illuminate\Support\Collection;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * class dùng trong việc theo dõi tin môi giới
 */
class TrackingPost extends Model
{
    protected $fillable = ['phone'];

    /**
     * Undocumented function
     *
     * @param string $phone
     * @return static
     */
    public static function findByPhoneOrCreate(string $phone)
    {
        return self::where('phone', $phone)->firstOrCreate([
            'phone' => $phone
        ]);
    }

    public function tracking()
    {
        $phone = Post::with('metas', 'categories')->filterRequest(['phone' => $this->phone])->get();

        if (empty($phone)) {
            return $this->forceFill([
                'seen' => 0,
                'district_unique' => 0,
                'categories_unique' => 0,
            ])->save();
        }

        $categoriesUnique = $this->trackingCategoriesUnique(
            $phone->reduce(function (Collection $carry, $item)
            {
                return $carry->push(...$item->categories);
            }, collect())
        );

        $districtUnique = $this->trackingDistrictUnique(
            $phone->reduce(function (Collection $carry, $item)
            {
                return $carry->push(...$item->metas);
            }, collect())
        );

        return $this->forceFill([
            'seen' => $phone->count(),
            'district_unique' => $districtUnique,
            'categories_unique' => $categoriesUnique,
        ])->save();
    }


    private function trackingCategoriesUnique(Collection $categories)
    {
        return $categories->unique('_id')->count();
    }

    private function trackingDistrictUnique(Collection $meta)
    {
        return $meta->where('name', PostMeta::District)->unique('value')->count();
    }
}
