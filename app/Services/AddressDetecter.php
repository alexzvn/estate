<?php

namespace App\Services;

use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Location\Ward;
use Illuminate\Support\Collection;

class AddressDetecter
{
    protected string $content;

    protected Collection $wards;

    protected Collection $provinces;

    protected Collection $districts;

    public function __construct(string $content = '') {
        $this->content = $content;

        $this->provinces = Province::active()->get();

        $this->districts = District::whereIn('province_id', $this->provinces->pluck('id'))->get();

        $this->wards     = Ward::whereIn('district_id', $this->districts->pluck('id'))->get();
    }

    public function province()
    {
        if (($district = $this->district()) && $district->province) {
            return $district->province;
        }

        foreach ($this->provinces as $province) {
            if ($province->match($this->content)) {
                return $province;
            }
        }

        return null;
    }

    public function district()
    {
        if (($ward = $this->ward()) && $ward->district) {
            return $ward->district;
        }

        foreach ($this->districts as $district) {
            if ($district->match($this->content)) {
                return $district;
            }
        }

        return null;
    }

    public function ward()
    {
        foreach ($this->wards as $ward) {
            if ($ward->match($this->content)) {
                return $ward;
            }
        }

        return null;
    }
}

