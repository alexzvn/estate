<?php

use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Location\Ward;

$address = json_decode(file_get_contents(base_path('address.json')));

function build_districts($province) : array
{
    return empty($province->level2s) ? [] : $province->level2s;
}

function build_ward($district) : array
{
    return empty($district->level3s) ? [] : $district->level3s;
}

foreach ($address->data as $province) {
    $provinceModel = Province::create([
        'name' => $province->name ?? '',
        'type' => $province->type ?? ''
    ]);

    foreach (build_districts($province) as $district) {

        $districtModel = District::create([
            'name' => $district->name ?? '',
            'type' => $district->type ?? ''
        ]);

        foreach (build_ward($district) as $ward) {
            $wardModel = Ward::create([
                'name' => $ward->name ?? '',
                'type' => $ward->type ?? ''
            ]);

            $districtModel->wards()->save($wardModel);
        }

        $provinceModel->districts()->save($districtModel);
    }
}
