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
    const PostFee      = 'Tin Xin Phí';

    const PostSellRent = 'Tin Mua Bán - Thuê';

    const PostMarket   = 'Tin Thị Trường';

    const Online       = 'Tin web online';
}
