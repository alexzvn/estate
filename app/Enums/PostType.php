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
    const Sell =   'Tin mua/bán';

    const Rent =   'Tin cho thuê';

    const Market = 'Tin thị trường';
}
