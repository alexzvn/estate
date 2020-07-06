<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SuperAdmin()
 * @method static static Staff()
 */
final class Role extends Enum
{
    const SuperAdmin = "Super Admin";
    const Staff      = "Staff";
}
