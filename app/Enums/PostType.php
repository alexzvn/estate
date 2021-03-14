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
}
