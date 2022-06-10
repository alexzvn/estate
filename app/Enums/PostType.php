<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Sell()
 * @method static static Rent()
 * @method static static Market()
 */
final class PostType extends Enum
{
    const PostFee      = 1;

    const PostSellRent = 2;

    const PostMarket   = 3;

    const Online       = 4;

    public static function getDescription($value): string
    {
        $mapType = [
            self::PostFee => 'Tin Xác Thực',
            self::PostSellRent => 'Tin mua bán - thuê',
            self::PostMarket => 'Tin thị trường',
            self::Online => 'Tin web online',
        ];

        return $mapType[$value] ?? parent::getDescription($value);
    }
}
