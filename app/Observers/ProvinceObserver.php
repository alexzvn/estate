<?php

namespace App\Observers;

use App\Models\Location\Province;
use App\Models\Permission;

class ProvinceObserver
{
    /**
     * Handle the province "created" event.
     *
     * @param  \App\App\Models\Province  $province
     * @return void
     */
    public function created(Province $province)
    {
        Permission::create([
            'name' => "post.province.access.$province->id",
            'display_name' => $province->name
        ]);
    }

    /**
     * Handle the province "updated" event.
     *
     * @param  \App\App\Models\Province  $province
     * @return void
     */
    public function updated(Province $province)
    {

        if ($province->active && $perm = Permission::findOrCreate("post.province.access.$province->id")) {
            $perm->fill(['display_name' => $province->name])->save();
        } else {
            $this->deleted($province);
        }
    }

    /**
     * Handle the province "deleted" event.
     *
     * @param  \App\App\Models\Province  $province
     * @return void
     */
    public function deleted(Province $province)
    {
        Permission::findByName("post.province.access.$province->id")->delete();
    }
}
