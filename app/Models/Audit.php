<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use OwenIt\Auditing\Audit as AuditingAudit;
use OwenIt\Auditing\Contracts\Audit as ContractsAudit;

class Audit extends Model implements ContractsAudit
{
    use AuditingAudit;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'old_values'   => 'json',
        'new_values'   => 'json',
        // Note: Please do not add 'auditable_id' in here, as it will break non-integer PK models
    ];
}
