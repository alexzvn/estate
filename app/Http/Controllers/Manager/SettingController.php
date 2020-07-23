<?php

namespace App\Http\Controllers\Manager;

use App\Http\Requests\Manager\SaveSetting;
use App\Repository\Location\Province;
use App\Repository\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('dashboard.setting', [
            'provinces' => Province::all()
        ]);
    }

    public function update(SaveSetting $request, Setting $setting)
    {
        Province::whereIn('_id', $request->provinces)->update(['active' => true]);
        Province::whereNotIn('_id', $request->provinces)->update(['active' => false]);

        $setting->setConfigs($request->only('title'));

        return redirect(route('manager.setting'))->with('success', 'Cập nhật thành công');
    }
}
