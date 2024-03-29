<?php

namespace App\Http\Controllers\Manager;

use App\Http\Requests\Manager\SaveSetting;
use App\Repository\Location\Province;
use App\Repository\Role;
use App\Setting;

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
        Province::whereIn('id', $request->provinces)->update(['active' => true]);
        Province::whereNotIn('id', $request->provinces)->update(['active' => false]);

        $setting->fill([
            'title'             => $request->title,
            'user.role.default' => $request->role,
            'notification'      => $request->notification,
            'google.analytics'  => $request->google_analytics,
            'post.reverse'      => (bool) $request->reverse,
            'popup.online'      => $request->online,
            'popup.fee'         => $request->fee,
            'popup.market'      => $request->market,
        ]);

        return redirect(route('manager.setting'))->with('success', 'Cập nhật thành công');
    }
}
