<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Builder;

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

    public function posts()
    {
        return $this->hasMany(Post::class, 'phone', 'phone');
    }

    public function tracking()
    {
        return $this->forceFill([
            'seen' => $this->posts->count(),
            'district_unique' => $this->countDistrict(),
            'categories_unique' => $this->countProvince(),
        ])->save();
    }

    public function countProvince()
    {
        return $this->posts->unique('province_id')->count();
    }

    public function countDistrict()
    {
        return $this->posts->unique('district_id')->count();
    }
}
