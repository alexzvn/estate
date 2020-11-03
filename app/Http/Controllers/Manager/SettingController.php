<?php

namespace App\Http\Controllers\Manager;

use App\Http\Requests\Manager\SaveSetting;
use App\Repository\Location\Province;
use App\Repository\Role;
use App\Repository\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('dashboard.setting', [
            'provinces' => Province::all(),
            'roles' => Role::customer()->get(),
        ]);
    }

    public function update(SaveSetting $request, Setting $setting)
    {
        Province::whereIn(id, $request->provinces)->update(['active' => true]);
        Province::whereNotIn(id, $request->provinces)->update(['active' => false]);

        $setting->setConfigs([
            'title' => $request->title,
            'user.role.default' => $request->role,
            'notification' => $request->notification,
            'google.analytics' => $request->google_analytics,
            'post.reverse' => (bool) $request->reverse
        ]);

        $setting->saveConfig();

        return redirect(route('manager.setting'))->with('success', 'Cập nhật thành công');
    }
}
