@extends('dashboard.app')

@push('style')
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/elements/tooltip.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/tables/table-basic.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/forms/switches.css') }}">
@endpush
@push('meta')
    <meta name="user_id" content="{{ $user->id }}">
@endpush

@section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
        <div class="row">
        <div class="col-md-8 mb-4">
            <form id="update-form" action="{{ route('manager.customer.update', ['id' => $user->id]) }}" method="POST">
            @csrf
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Thông tin {{ $user->name }}

                                @if ($user->isBanned())
                                    @can('manager.customer.pardon')
                                        <a href="{{ route('manager.customer.pardon', ['id' => $user->id]) }}" class="btn btn-sm btn-outline-warning"> Mở khóa</a>
                                    @endcan

                                    @else

                                    @can('manager.customer.ban')
                                        <a href="{{ route('manager.customer.ban', ['id' => $user->id]) }}" class="btn btn-sm btn-outline-danger"> Khóa tài khoản</a>
                                    @endcan
                                @endif
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="form-group input-group-sm">
                      <label for="name">Họ Tên</label>
                      <input type="text" value="{{ $user->name }}" name="name" id="name" class="form-control" placeholder="Họ tên tài khoản" required>
                    </div>

                    <div class="form-group input-group-sm">
                      <label for="phone">
                          Số điện thoại
                            @if (! $user->hasVerifiedPhone())
                            <a @can('manager.customer.verify.phone') href="{{ route('manager.customer.verify.phone', ['id' => $user->id]) }}" @endcan class="badge badge-warning bs-tooltip" title="Nhấn để xác thực SĐT">Chưa xác thực</a>
                            @else
                            <a @can('manager.customer.verify.phone') href="{{ route('manager.customer.unverified.phone', ['id' => $user->id]) }}" @endcan class="badge badge-success bs-tooltip" title="Nhấn để bỏ xác thực SĐT"> Đã xác thực</a>
                            @endif
                      </label>
                      <input type="text" value="{{ $user->phone }}" name="phone" id="phone" class="form-control" placeholder="Số điện thoại" required>
                    </div>

                    <div class="form-group input-group-sm">
                      <label for="email">Email</label>
                      <input type="email" value="{{ $user->email }}" name="email" id="email" class="form-control" placeholder="Email" required>
                    </div>

                    @can('manager.user.assign.customer')
                        <div class="form-group input-group-sm">
                          <label for="supporter_id">Chọn CSKH</label>
                          <select class="form-control" name="supporter_id" id="supporter_id">
                            <option value="">Trống</option>
                            @foreach ($staffs as $staff)
                                <option value="{{ $staff->id }}" {{ $staff->id === $user->supporter_id ? 'selected' : '' }}>{{ $staff->name }}</option>
                            @endforeach
                          </select>
                        </div>
                    @endcan

                    <hr>

                    <a href="javascript:void(0)" id="change-password" class="text-info">Đặt lại mật khẩu?</a>

                    <div class="mt-3" id="change-password-input" style="display: none;">
                        <div class="form-group input-group-sm">
                            <label for="password">Mật khẩu mới</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Mật khẩu mới cho tài khoản này">
                          </div>
                          <div class="form-group input-group-sm">
                            <label for="password_confirm">Nhập lại mật khẩu</label>
                            <input type="password" class="form-control" name="password_confirm" id="password_confirm" placeholder="Nhập lại mật khẩu mới">
                          </div>
                    </div>

                </div>
            </div>
            </form>

            <div class="statbox widget box box-shadow my-3">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Các gói đã đăng ký</h4>

                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                            <thead>
                                <tr>
                                    <th class="checkbox-column text-center">
                                        @can('manager.subscription.delete')
                                        <a class="delete-sub" href="javascript:void(0)">
                                            <i class="text-danger" data-feather="trash-2"></i>
                                        </a>
                                        @endcan
                                    </th>
                                    <th class="">Tên gói</th>
                                    <th class="">Bắt đầu</th>
                                    <th class="">Hết hạn</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Khóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user->subscriptions as $item)
                                @if ($plan = $item->plan)
                                <tr>
                                    <td class="checkbox-column">
                                        <div class="custom-control custom-checkbox checkbox-primary">
                                        <input type="checkbox" class="custom-control-input todochkbox" id="check-{{ $loop->index }}" name="subscriptions[]" value="{{ $item->id }}">
                                        <label class="custom-control-label" for="check-{{ $loop->index }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0">{{ $plan->name }}</p>
                                    </td>
                                    <td>{{ $item->activate_at ? $item->activate_at->format('d/m/Y') : 'N/a' }}</td>
                                    <td>{{ $item->expires_at ? $item->expires_at->format('d/m/Y') : 'N/a' }}</td>
                                    <td class="text-center">
                                        @if ($item->isActivated())
                                            <span id="badge-{{ $item->id }}" class="badge badge-success">Đang hoạt động</span>
                                        @else
                                            <span id="badge-{{ $item->id }}" class="badge badge-warning">Ngừng hoạt động</span>
                                        @endif
                                    </td>

                                    @can('manager.subscription.lock')
                                    <td class="text-center">
                                        <label class="switch s-outline s-outline-info">
                                            <input class="lock" type="checkbox" data-id="{{ $item->id }}" {{ $item->lock ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    @endcan
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Tác vụ</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    @can('manager.customer.modify')
                    <a id="submit" class="btn btn-primary">Cập Nhật</a>
                    @endcan
                </div>
            </div>

            @can('manager.order.create')
            @if ($user->hasVerifiedPhone())
            <div class="statbox widget box box-shadow mt-3">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Đăng ký gói mới cho khách hàng</h4>
        
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">

                    <form action="{{ route('manager.customer.order.store', ['id' => $user->id]) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="plans">Chọn các gói</label>
                            <select class="form-control tagging" name="plans[]" id="plans" multiple required>
                                @foreach ($plans as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">Tiếp tục</button>
                    </form>
                </div>
            </div>
            @endif
            @endcan

        </div>
    </div>
</div>

<form id="delete-sub-form" action="#" method="post">@csrf</form>
@endsection

@push('script')
<script src="{{ asset('dashboard/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('dashboard/plugins/input-mask/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('dashboard/assets/js/elements/tooltip.js') }}"></script>
<script>
(function (window) {
    $(".tagging").select2({
        tags: true
    });

    $('#submit').click(function () {
        if (confirm('Bạn có chắc muốn thực hiện các thay đổi này?')) {
            document.getElementById('update-form').submit();
        }
    });

    $('#change-password').click(function () {
        $('#change-password-input').fadeIn();
    });

    $('#phone').inputmask("9999.999.999");

    $(document).ready(function () {
        $('.delete-sub').on('click', function () {
            let id = $(this).data('id');
            let form = $('#delete-sub-form');

            if (confirm('Bạn có thật sự muốn xóa gói đăng ký này?')) {
                form.attr('action', `/manager/customer/subscription/${id}/delete`);
                form.submit();
            }
        });

        $('.lock').on('click', function () {
            let id = $(this).data('id');
            fetch(`/manager/customer/subscription/${id}/lock/toggle`)
                .then(function () {
                    $('#badge-' + id).html()
                });
        });
    });
}(window));
</script>
@endpush