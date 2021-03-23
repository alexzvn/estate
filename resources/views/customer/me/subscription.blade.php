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
        @if ($subscriptions->isNotEmpty())
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên</th>
                    <th>Trạng thái</th>
                    <th>Kích hoạt</th>
                    <th>Hết hạn</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subscriptions as $sub)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sub->plan->name }}</td>
                    <td>
                        @if($sub->isActivated())
                            <span class="badge badge-success">Đang hoạt động</span>
                        @else
                            <span class="badge badge-warning">Đã hết hạn</span>
                        @endif
                    </td>
                    <td>{{ $sub->activate_at }}</td>
                    <td>{{ $sub->expires_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="jumbotron">
            <h1 class="display-5">Bạn chưa đăng ký dịch vụ nào</h1>
            <p class="lead">Hãy bắt đầu đăng ký và xem tin từ ngay hôm nay!</p>
        </div>
        @endif

        {{-- {{ $subscriptions->render() }} --}}
    </div>
</div>
@endsection
