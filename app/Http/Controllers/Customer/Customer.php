<?php

namespace App\Http\Controllers\Customer;

use App\Models\Plan;
use App\Enums\PostType;
use App\Models\Category;
use Illuminate\Support\Carbon;
use App\Models\Location\Province;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Customer\UpdateInfo;
use App\Models\Order;
use Illuminate\Http\Request;

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

    /**
     * Show price
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function plans()
    {
        return view('customer.me.plan.index', [
            'plans' => Plan::forCustomer()->get(),
            'postTypes' => PostType::getValues(),
            'provinces' => Province::active()->get(),
            'categories' => Category::parentOnly()->get(),
        ]);
    }

    public function registerOrder(Request $request)
    {
        $this->validate($request, [
            'plans' => 'array|exists:plans,_id',
            'month' => 'numeric|max:12|min:1'
        ]);

        $order = user()->orders()->create([
            'manual' => false,
            'month'  => (int) $request->month,
            'status' => Order::PENDING
        ]);

        $order->plans()->sync($request->plans);

        $order->writeNote('Yêu cầu gia hạn');

        return back()->with('success', $order);
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
