<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static TinChinhChu()
 * @method static static LocTinBds()
 * @method static static ChoTot()
 */
final class PostSource extends Enum
{
    const TinChinhChu         = 0;
    const LocTinBds = 1;
    const ChoTot     = 2;
}
