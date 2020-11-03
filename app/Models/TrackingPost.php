<?php

namespace App\Models;

use App\Enums\PostType;
use Illuminate\Database\Eloquent\Model;

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
            'seen' => $this->onlinePosts()->count(),
            'district_unique' => $this->countDistrict(),
            'categories_unique' => $this->countProvince(),
        ])->save();
    }

    public function countProvince()
    {
        return $this->onlinePosts()->unique('province_id')->count();
    }

    public function countDistrict()
    {
        return $this->onlinePosts()->unique('district_id')->count();
    }

    public function onlinePosts()
    {
        static $post;

        if (is_null($post)) {
            $post = $this->posts()->where('type', PostType::Online)->get();
        }

        return $post;
    }
}
