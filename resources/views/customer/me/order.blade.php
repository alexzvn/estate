@extends('customer.me.components.app')

@php
function planToString($plans) {
    if (! $plans) return 'N/A';

    $carry = $plans->reduce(function ($carry, $item) {
        $carry[] = $item->name;

        return $carry;
    }, []);

    return implode('<br>', $carry);
}
@endphp

@section('main-content')
<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Đơn hàng</th>
                    <th>Giá trị</th>
                    <th>Trạng thái</th>
                    <th>Tạo bởi</th>
                    <th>Lúc tạo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{!! planToString($order->plans) !!}</td>
                    <td>{{ $order->after_discount_price ? number_format($order->after_discount_price) : number_format($order->price) }}đ</td>
                    <td>@include('dashboard.order.status', ['status' => $order->status])</td>
                    <td>{{ $order->creator->name ?? 'n/A' }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i:s') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $orders->render() }}
    </div>
</div>
@endsection
