<?php

namespace App\Repository\Location;

use App\Models\Location\VietNam\Ward as VietNamWard;
use App\Repository\BaseRepository;

class Ward extends BaseRepository
{
    public function __construct(VietNamWard $model) {
        $this->model = $model;
    }
}
