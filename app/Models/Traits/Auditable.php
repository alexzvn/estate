<?php

namespace App\Models\Traits;

use OwenIt\Auditing\Auditable as AuditingAuditable;

trait Auditable
{
    use AuditingAuditable;

    public function withoutAudit(\Closure $handle)
    {
        static::disableAuditing();

        $handle();

        static::enableAuditing();
    }
}