@extends('dashboard.app')

@push('style')
<link rel="stylesheet" href="{{ asset('dashboard/assets/css/tables/table-basic.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/plugins/table/datatable/dt-global_style.css') }}">
@endpush

@section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>
                        Lịch sử hoạt động khách hàng
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
                            <th>Họ tên</th>
                            <th>Số điện thoại</th>
                            <th>Hoạt động</th>
                            <th>Thời gian</th>
                            <th>Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                        <tr>
                            <td class="text-center" >{{ $loop->index }}</td>
                            <td><a class="text-primary font-weight-bolder" href="{{ route('manager.customer.view', ['id' => $log->user->id]) }}">{{ $log->user->name }}</a></td>
                            <td>{{ $log->user->phone }}</td>
                            <td>{{ $log->content }}</td>
                            <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                @if ($log->link)
                                <a class="text-info" href="{{ $log->link }}" target="_blank">Xem</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $logs->onEachSide(0)->withQueryString()->render() }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
