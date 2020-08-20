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
    public function index(Request $request)
    {
        $this->authorize('manager.order.view');

        $order = Order::with(['plans', 'customer', 'creator'])->filterRequest(['search' => $request->get('query')])->latest();

        $order->whereHas('customer', function ($q) use ($request)
        {
            if ($request->me) {
                $q->where('supporter_id', $request->user()->id);
            }
        });

        if ($date = $request->expires_date) {
            $date = Carbon::createFromFormat('d/m/Y', $date);

            $order->whereBetween('expires_at', [$date->startOfDay(), $date->endOfDay()]);
        }

        return view('dashboard.order.index', [
            'orders' => $order->paginate(40)
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
        $order = Order::findOrFail($id)->forceFill(['manual' => (bool) $request->manual]);

        $hasNotOrdered = $order->status !== ModelsOrder::PAID && is_null($order->verifier);

        if ($order->status == ModelsOrder::PENDING || $request->user->can('manager.order.modify.force')) {
            $request->manual ?
                $this->updateManual($order, $request)->save():
                $this->updateAuto($order, $request)->save();
        }

        $order->writeNote($request->note ?? '');

        if ($hasNotOrdered && $request->verified ) { //activate order in first time
            (new Customer($order->customer))->renewSubscription($order);
            $order->activate_at = $request->activeAt();
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
