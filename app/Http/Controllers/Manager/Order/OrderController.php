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

        $hasNotOrdered = $order->status !== ModelsOrder::PAID && is_null($order->verifier);

        if ($order->status === ModelsOrder::PENDING || $this->authorize('manager.category.modify.force')) {
            $request->manual ?
                $this->updateManual($order, $request)->save():
                $this->updateAuto($order, $request)->save();
        }

        if ($hasNotOrdered && $request->verified ) { //activate order in first time
            (new Customer($order->customer))->renewSubscription($order);
            $order->verifier_id = $request->user()->id;
            $order->save();
        }

        return redirect(route('manager.order.view', ['id' => $order->id]));
    }

    protected function updateAuto(ModelsOrder $order, UpdateOrder $request)
    {
        $order->fill($request->all())->fill([
            'month' => (int) $request->expires_month,
            'price' => $order->plans->sum('price') ?? 0,
            'status'=> $request->verified ? ModelsOrder::PAID : ModelsOrder::PENDING,
            'verified' => (bool) $request->verified,
            'activate_at' => $request->activeAt(),
            'expires_at' => $request->expiresAt(),
        ]);

        return $this->orderCalcDiscount($order, $order->price * $order->month);
    }

    protected function updateManual(ModelsOrder $order, UpdateOrder $request)
    {
        $price = (int) str_replace(',', '', $request->price ?? 0);

        $order->fill($request->all())->fill([
            'month' => null,
            'price' => $price,
            'status'=> $request->verified ? ModelsOrder::PAID : ModelsOrder::PENDING,
            'verified' => (bool) $request->verified,
            'activate_at' => $request->activeAt(),
            'expires_at' => $request->expiresAt(),
        ]);

        return $this->orderCalcDiscount($order, $order->price);
    }

    private function orderCalcDiscount(ModelsOrder $order, int $price)
    {
        if ($order->discount_type == ModelsOrder::DISCOUNT_NORMAL) {
            $price -= $order->discount;
        } else {
            $price -= $price * ($order->discount/100);
        }

        $order->after_discount_price = $price;

        return $order;
    }
}
