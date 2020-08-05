<?php

namespace App\Repository;

use App\Models\Report as ReportModel;

class Report extends BaseRepository
{
    public function __construct(ReportModel $model) {
        $this->setModel($model);
    }
}
