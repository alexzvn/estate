<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Draft()
 * @method static static Pending()
 * @method static static Published()
 */
final class PostStatus extends Enum
{
    const Draft     = '0';

    const Locked    = '3';

    /**
     * Waiting post need action from admin
     */
    const Pending   = '1';

    const Published = '2';
}
