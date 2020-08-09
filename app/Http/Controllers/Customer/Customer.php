<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Customer\UpdateInfo;
use Illuminate\Support\Carbon;

class Customer extends Controller
{
    public function me()
    {
        return view('customer.customer',[
            'customer' => Auth::user()
        ]);
    }

    public function update(UpdateInfo $request)
    {
        $user = Auth::user();

        $user->forceFill(
            $request->only(['name', 'email', 'address'])
        )->save();

        if ($request->has('birthday') && $request->birthday) {
            $user->forceFill([
                'birthday' => Carbon::createFromFormat('Y-m-d', $request->birthday)
            ])->save();
        }

        if ($password = $request->password) {

            if (! Hash::check($request->password_old, $user->getAuthPassword())) {
                return back()->withErrors(['password_old' => ['Mật khẩu cũ của bạn không đúng']]);
            }

            $user->forceFill(['password' => Hash::make($password)])->save();
        }

        return back()->with('success', 'Cập nhật thành công');
    }
}
