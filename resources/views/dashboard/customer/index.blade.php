@extends('dashboard.app')

@push('style')
<link rel="stylesheet" href="{{ asset('dashboard/assets/css/tables/table-basic.css') }}">
<style>
    .user-active td {
        color: #1b55e2 !important;
    }

    .user-less-3-days td {
        color: #f72c38 !important;
    }
</style>
@endpush

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
            @include('dashboard.customer.components.search')

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
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
                            // khách hoạt động: xanh nhẹ
                            // Còn 3 ngày: cam
                            // hết hạn: xan

                            $subs = $user->subscriptions->sort(function ($a, $b)
                            {
                                return $b->created_at <=> $a->created_at;
                            });

                            $sub  = $subs->first();

                            if ($sub) {
                                if (now()->addDays(3)->lessThan($sub->expires_at)) {
                                    $class = 'user-active';
                                } elseif (now()->lessThan($sub->expires_at) && now()->addDays(3)->greaterThan($sub->expires_at)) {
                                    $class = 'user-less-3-days';
                                } else {
                                    $class = '';
                                }
                            } else {
                                $class = '';
                            }

                        @endphp
                        <tr class="{{ $class }}">
                            <td class="text-center" >{{ $loop->index }}</td>
                            <td class="cursor-pointer open-user" data-id="{{ $user->id }}" style="font-weight: bold">{{ $user->name }} @if($user->hasVerifiedPhone()) <i class="text-success" width="15" height="15" data-feather="check-circle"></i> @endif</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ number_format($user->orders->sum('after_discount_price')) }} đ</td>
                            <td>{{ $sub && $sub->activate_at ? $sub->activate_at->format('d/m/Y') : 'N/a' }}</td>
                            <td>{{ $sub && $sub->expires_at ? $sub->expires_at->format('d/m/Y') : 'N/a' }}</td>
                            <td><a class="text-primary" href="{{ route('manager.log') }}?phone={{ $user->phone }}">Xem</a></td>
                            <td>
                                @if ($supporter = $user->supporter)
                                    <span class="text-info">{{ $supporter->id == Auth::id() ? 'Bạn' : "$supporter->name" }}</span>
                                @else
                                N/a
                                @endif
                            </td>
                            <td class="text-center">
                                <ul class="table-controls">
                                    @can('manager.subscription.lock')
                                        <li>
                                            @if ($user->isBanned())
                                                <a href="{{ route('manager.customer.pardon', ['id' => $user->id]) }}"><i class="text-danger" data-feather="lock"></i></a>
                                            @else
                                                <a href="{{ route('manager.customer.ban', ['id' => $user->id]) }}"><i data-feather="unlock"></i></a>
                                            @endif
                                        </li>
                                    @endcan
                                    @if (Auth::user()->can('manager.customer.logout'))
                                        @php
                                            $isOnline = $user->isOnline();
                                        @endphp
                                        <li>
                                            <a href="{{ $isOnline ? route('manager.customer.logout', ['id' => $user->id]) : 'javascript:void(0)' }}">
                                                <i class="{{ $isOnline ? 'text-danger' : '' }}" data-feather="log-out"></i>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
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

@push('script')
@if (($supporter && $supporter->id == Auth::id()) || Auth::user()->can('manager.user.assign.customer'))
<script>
    $(document).ready(function () {
        $('.open-user').on('click', function () {
            let id = $(this).data('id');

            window.location.href = `/manager/customer/${id}/view`;
        });
    });
</script>
@endif
@endpush