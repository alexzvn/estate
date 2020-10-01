@extends('dashboard.app')

@push('style')
<link rel="stylesheet" href="{{ asset('dashboard/assets/css/tables/table-basic.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/datepicker/css/bootstrap-datepicker.standalone.min.css') }}">
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
                    <div class="col-md-4 pl-md-0 order-first">
                        <div class="form-row">
                            <label for="phone" class="col-md-3 col-form-label text-md-right d-none d-md-block"><strong>Tìm kiếm: </strong></label>
            
                            <div class="col-md-9">
                                <div class="form-group input-group-sm">
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ request('phone') }}" placeholder="Tìm theo sđt">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 order-first">
                        <div class="form-group input-group-sm">
                          <select class="form-control" name="user" id="user">
                            <option value="">Nhân viên</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request('user') === $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>

                    <div class="col-md-2 order-first">
                        <div class="form-group input-group-sm">
                            <input type="text" class="form-control" name="from" id="from" data-date-orientation="bottom auto"
                              data-provide="datepicker" placeholder="Chọn từ ngày..." value="{{ request('from') }}" data-date-format="dd-mm-yyyy">
                              <small class="ml-2 form-text text-muted">Chọn từ ngày</small>
                          </div>
                    </div>
                    <div class="col-md-2 order-first">
                        <div class="form-group input-group-sm">
                          <input type="text" class="form-control" name="to" id="to" data-date-orientation="bottom auto"
                          data-provide="datepicker" placeholder="Đến ngày" value="{{ request('to') }}" data-date-format="dd-mm-yyyy">
                          <small class="ml-2 form-text text-muted">Chọn đến ngày</small>
                        </div>
                    </div>

                    <div class="col-md-2 pl-md-0 order-md-first order-last">
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
                            <td>{{ $audit->user->name }} đã {{ $type[$audit->event] ?? 'tác động' }} {{ $audit->auditable_type::NAME }} vào khoảng {{ $audit->created_at->diffForHumans() }}</td>
                            <td>{{ $audit->created_at->format('d/m/Y H:i:s') }}</td>
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

@push('script')
<script src="{{ asset('assets/vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datepicker/locales/bootstrap-datepicker.vi.min.js') }}"></script>
<script>
(function (window) {

    let advancedSearch;

    if ((openSearch = getCookie('open_search')) && openSearch !== '') {
        advancedSearch = openSearch === 'true';
        if (advancedSearch) {
            $('#advanced-search-form').show();
        }

    } else {
        advancedSearch = false;
        setCookie('open_search', 'false');
    }

    $(document).ready(function () {
        $('#advanced-search').click(function () {
            if (advancedSearch = !advancedSearch) {
                $('#advanced-search-form').fadeIn();
            } else {
                $('#advanced-search-form').fadeOut();
            }

            setCookie('open_search', ''+ advancedSearch)
        });

        $('#search-form').change(function name() {
            $('#search-form').submit();
        });
    });

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
            c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function setCookie(cname, cvalue) {
        var d = new Date();
        d.setTime(d.getTime() + (2 * 60 * 1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

}(window));
</script>
@endpush