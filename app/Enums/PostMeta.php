<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Province()
 * @method static static City()
 * @method static static District()
 * @method static static Street()
 */
final class PostMeta extends Enum
{
    const Province   = 'Address Province';
    const City       = 'Address City';
    const District   = 'Address District';
    const Street     = 'Address Street';
    const Phone      = 'Phone';
    const Price      = 'Price';
    const Commission = 'Commission';
}
