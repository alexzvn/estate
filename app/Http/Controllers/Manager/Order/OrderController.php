<?php

namespace App\Http\Controllers\Manager\Order;

use App\Repository\Order;
use App\Http\Controllers\Manager\Controller;
use App\Http\Requests\Manager\Order\UpdateOrder;
use App\Models\Order as ModelsOrder;
use App\Services\Customer\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $this->authorize('manager.order.view');

        return view('dashboard.order.index', [
            'orders' => Order::with(['plans', 'customer'])->paginate()
        ]);
    }

    public function view(string $id, Order $order)
    {
        $this->authorize('manager.order.view');

        $order = $order->findOrFail($id);

        return view('dashboard.order.view', [
            'customer' => $order->customer,
            'plans' => $order->plans,
            'order' => $order,
        ]);
    }

    public function delete(string $id, Order $order)
    {
        $this->authorize('manager.order.delete');

        $order->findOrFail($id)->delete();

        return redirect(route('manager.order'))->with('success', 'Xóa thành công');
    }

    public function update(string $id, UpdateOrder $request)
    {
        $order = Order::findOrFail($id);

        $startAt = $request->activated_at ? Carbon::createFromTimestamp(strtotime($request->activated_at)) : now();

        if (! $order->verified || $order->status === ModelsOrder::PENDING) {
            (new Customer($order->customer))->renewSubscription($order);
        }

        $order->fill($request->all())->fill([
            'month' => $request->expires_month,
            'price' => $order->plans->sum('price') ?? 0,
            'status'=> ModelsOrder::PAID,
            'verified' => true,
            'activate_at' => $startAt,
            'expires_at' => $this->getExpires($request),
        ]);

        $order = $this->calcOrder($order);

        $order->save();

        return redirect(route('manager.order.view', ['id' => $order->id]));
    }

    private function getExpires(Request $request)
    {
        if ($request->expires_at) {
            return Carbon::createFromTimestamp(strtotime($request->expires_at));
        } elseif($request->expires_month ) {
            return now()->addMonths($request->expires_month);
        }

        return null;
    }

    private function calcOrder(ModelsOrder $order)
    {
        $price = $order->price * $order->month;

        if ($order->discount_type == ModelsOrder::DISCOUNT_NORMAL) {
            $price -= $order->discount;
        } else {
            $price -= $price * ($order->discount/100);
        }

        $order->after_discount_price = $price;

        return $order;
    }
}
