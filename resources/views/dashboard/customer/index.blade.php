@extends('dashboard.app')

@section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>
                        Danh sách thành viên
                        @can('manager.customer.create')
                        <a href="{{ route('manager.customer.create') }}" class="btn btn-success rounded-circle"><i data-feather="plus"></i></a>
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
                            <th>Họ tên</th>
                            <th>Số điện thoại</th>
                            <th>Đã chi</th>
                            <th>Đăng ký</th>
                            <th>Hết hạn</th>
                            <th>Hđ gần nhất</th>
                            <th>CSKH</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        @php
                            $orderFirst = $user->orders->first();
                            $order = $user->orders->last();
                        @endphp
                        <tr>
                            <td class="text-center" >{{ $loop->index }}</td>
                            <td style="font-weight: bold">{{ $user->name }} @if($user->hasVerifiedPhone()) <i class="text-success" width="15" height="15" data-feather="check-circle"></i> @endif</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ number_format($user->orders->sum('after_discount_price')) }} đ</td>
                            <td>{{ $orderFirst && $orderFirst->activate_at ? $orderFirst->activate_at->format('d/m/Y') : 'N/a' }}</td>
                            <td>{{ $order && $order->expires_at ? $order->expires_at->format('d/m/Y') : 'N/a' }}</td>
                            <td>{{ $order && $order->created_at ? $order->created_at->format('d/m/Y') : 'N/a'  }}</td>
                            <td>
                                @if ($supporter = $user->supporter)
                                    <span class="text-info">{{ $supporter->id == Auth::id() ? 'Bạn' : "$supporter->name - $supporter->phone" }}</span>
                                @else
                                N/a
                                @endif
                            </td>
                            <td>
                                @if (($supporter && $supporter->id == Auth::id()) || Auth::user()->can('manager.user.assign.customer'))
                                <a href="{{ route('manager.customer.view', ['id' => $user->id]) }}">
                                    <i class="role-edit t-icon t-hover-icon" data-feather="edit"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {!! $users->appends($_GET)->render() !!}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection