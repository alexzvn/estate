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
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Đơn hàng</th>
                        <th>Giá trị</th>
                        <th>Trạng thái</th>
                        <th>Tạo bởi</th>
                        <th>Lúc tạo</th>
                        <th>Hết hạn</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{!! planToString($order->plans) !!}</td>
                        <td>{{ $order->total ? number_format($order->total) : number_format($order->price) }}đ</td>
                        <td>@include('dashboard.order.status', ['status' => $order->status])</td>
                        <td>{{ $order->creator->name ?? 'n/A' }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>
                            @if ($order->expires_at)
                                {{ $order->expires_at->format('d/m/Y H:is') }}
                            @elseif ($order->month && $order->activate_at)
                                {{ $order->activate_at->addMonths($order->month)->format('d/m/Y H:i:s') }}
                            @else
                            --
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $orders->render() }}
    </div>
</div>
@endsection
