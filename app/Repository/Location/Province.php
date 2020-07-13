<?php

namespace App\Repository\Location;

use App\Models\Location\VietNam\Province as VietNamProvince;
use App\Repository\BaseRepository;

class Province extends BaseRepository
{
    public function __construct(VietNamProvince $model) {
        $this->model = $model;
    }
}
