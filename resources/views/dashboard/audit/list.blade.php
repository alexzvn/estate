@extends('dashboard.app')

@push('style')
<link rel="stylesheet" href="{{ asset('dashboard/assets/css/tables/table-basic.css') }}">
@endpush

@php
$type = [
    'created' => 'tạo mới',
    'updated' => 'cập nhật',
    'deleted' => 'xóa bỏ',
    'restored' => 'khôi phục'
];

$color = [
    'created' => 'success',
    'updated' => 'primary',
    'deleted' => 'danger',
    'restored' => 'secondary'
];
@endphp

@section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>
                        Lịch sử hoạt động chung
                    </h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <form id="search-form" action="" method="GET">
                <div class="row">
                    <div class="col-md-5 pl-md-0 order-first">
                        <div class="form-row">
                            <label for="phone" class="col-md-3 col-form-label text-md-right d-none d-md-block"><strong>Tìm kiếm: </strong></label>
            
                            <div class="col-md-9">
                                <div class="form-group input-group-sm">
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ request('query') }}" placeholder="Tìm theo sđt">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 pl-md-0 order-md-first order-last">
                        <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
                        </a>
                    </div>
            
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-light mb-4">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Người dùng</th>
                            <th>Loại</th>
                            <th>Thông tin</th>
                            <th>Thời gian</th>
                            <th>IP</th>
                            <th>Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($audits as $audit)
                        <tr>
                            <td class="text-center" >{{ $loop->index }}</td>
                            <td><a class="text-primary font-weight-bolder" href="{{ route('manager.user.view', ['id' => $audit->user->id]) }}">{{ $audit->user->name }}</a></td>
                            <td><span class="text-{{ $color[$audit->event] ?? '' }}">{{ Str::ucfirst($type[$audit->event] ?? 'Không rõ') }}</span></td>
                            <td>{{ $audit->user->name }} đã {{ $type[$audit->event] ?? 'tác động' }} {{ $audit->auditable->getModelName() }}</td>
                            <td>{{ $audit->created_at->diffForHumans() }}</td>
                            <td> {{ $audit->ip_address }} </td>
                            <td>
                                <a class="text-info" href="{{ $audit->url }}" target="_blank">Xem</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <span class="text-muted">Tổng cộng có {{ $audits->total() }} bản ghi</span>

                <div class="d-flex justify-content-center">
                    {!! $audits->appends($_GET)->render() !!}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection