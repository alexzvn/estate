<?php

namespace App\Repository;

use App\Models\Order as ModelsOrder;

class Order extends BaseRepository
{
    public function __construct(ModelsOrder $model) {
        $this->setModel($model);
    }
}
