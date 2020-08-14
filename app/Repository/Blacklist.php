<?php

namespace App\Repository;

use App\Models\Blacklist as Model;

class Blacklist extends BaseRepository
{
    public function __construct(Model $blacklist) {
        $this->setModel($blacklist);
    }

    public static function findByPhone(string $phone)
    {
        return self::where('phone', $phone)->first();
    }

    public function findByPhoneOrCreate(string $phone)
    {
        return $phone = self::findByPhone($phone) ? $phone : self::create(['phone' => $phone]);
    }
}
