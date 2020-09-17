<?php

namespace App\Models\Traits;

use OwenIt\Auditing\Auditable as AuditingAuditable;

trait Auditable
{
    use AuditingAuditable;

    public static function withoutAudit(\Closure $handle)
    {
        static::disableAuditing();

        $handle();

        static::enableAuditing();
    }

    public function getModelName()
    {
        return $this->modelName ?? 'model';
    }
}