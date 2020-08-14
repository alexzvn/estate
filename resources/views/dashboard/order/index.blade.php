@extends('dashboard.app')

@php
function planToString($plans) {
    if (! $plans) return 'N/A';

    $carry = $plans->reduce(function ($carry, $item) {
        return $carry = "$carry, $item->name";
    }, '');

    return ltrim(ltrim($carry, ','));
}
@endphp

@section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>
                        Danh sách đơn hàng
                        @can('manager.user.create')
                        <button class="btn btn-success rounded-circle"><i data-feather="plus"></i></button>
                        @endcan
                    </h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-hover table-light mb-4">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th width="20%">Khách hàng</th>
                            <th>Số điện thoại</th>
                            <th>Các gói đăng ký</th>
                            <th>Giá tiền</th>
                            <th>Ngày kích hoạt</th>
                            <th>Ngày hết hạn</th>
                            <th>Giá tiền</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td class="text-center" >{{ $loop->index + 1 }}</td>
                            <td><a class="text-primary font-weight-bolder d-block" href="{{ route('manager.customer.view', ['id' => $order->customer->id]) }}">{{ $order->customer->name }}</a></td>
                            <td>{{ $order->customer->phone }}</td>
                            <td>{{ planToString($order->plans) }}</td>
                            <td>{{ $order->after_discount_price ? number_format($order->after_discount_price) : number_format($order->price) }}đ</td>
                            <td>{{ $order->activate_at ? $order->activate_at->format('d/m/Y H:i:s') : 'N/a' }}</td>
                            <td>{{ $order->expires_at ? $order->expires_at->format('d/m/Y H:i:s') : $order->month . ' tháng' }}</td>
                            <td>
                                <span class="badge badge-{{ $order->status === $order::PAID ? 'success' : 'primary' }}">
                                    {{ $order->status === $order::PAID ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('manager.order.view', ['id' => $order->id]) }}">
                                    <i class="role-edit t-icon t-hover-icon" data-feather="edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {!! $orders->appends($_GET)->render() !!}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection