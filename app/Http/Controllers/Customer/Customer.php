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
        return view('customer.me.customer',[
            'customer' => Auth::user()
        ]);
    }

    public function history()
    {
        return view('customer.me.history', [
            'logs' => user()->logs()->latest()->limit(20)->get()
        ]);
    }

    public function orders()
    {
        $orders = user()->orders()->latest();

        $orders->with(['creator', 'plans']);

        return view('customer.me.order', [
            'orders' => $orders->paginate(20)
        ]);
    }

    public function subscriptions()
    {
        $subs = user()->subscriptions();

        $subs->with('plan')->latest();

        return view('customer.me.subscription', [
            'subscriptions' => $subs->get()
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
