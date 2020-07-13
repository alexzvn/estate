<?php

namespace App\Repository\Location;

use App\Models\Location\VietNam\District as VietNamDistrict;
use App\Repository\BaseRepository;

class District extends BaseRepository
{
    public function __construct(VietNamDistrict $model) {
        $this->model = $model;
    }
}
