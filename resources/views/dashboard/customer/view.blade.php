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

                    <div class="form-group input-group-sm">
                      <label for="note">Ghi chú</label>
                      <textarea class="form-control" name="note" id="note" rows="3" placeholder="Nội dung ghi chú"></textarea>
                    </div>

                    @php
                        $notes = $user->note ? $user->note->audits()->with('user')->get() : collect();
                        $notes = $notes->reverse()
                            ->filter(function ($note) {
                                return ! empty($note->new_values['content']);
                            })
                    @endphp

                    @if ($notes->isNotEmpty())
                    <div class="form-group input-group-sm">
                        <label>Lịch sử ghi chú</label>
                        <textarea class="form-control" rows="6" readonly placeholder="lịch sử trống">@forelse ($notes as $note){{ ($loop->remaining + 1). ($note->user->name ?? '[deleted]') . " - {$note->created_at} \n" . $note->new_values['content'] . "\n\n" }}@empty Lịch sử trống @endforelse</textarea>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="provinces">Khu vực hoạt động: </label>
                        <select class="form-control tagging" name="provinces[]" id="provinces" multiple @cannot('*') disabled @endcannot>
                          @foreach ($provinces as $item)
                              <option value="{{ $item->id }}" {{ $user->provinces->where('id', $item->id)->isNotEmpty() ? 'selected' : '' }}>{{ $item->name }}</option>
                          @endforeach
                        </select>
                    </div>

                    <hr>

                    <div>
                        <a href="javascript:void(0)" id="change-password" class="text-info">Đặt lại mật khẩu?</a>
                        @can('manager.customer.delete')
                        <a href="javascript:void(0)" id="delete-account" data-id="{{ $user->id }}" class="text-danger float-right">Xóa tài khoản này?</a>
                        @endcan
                    </div>

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
                @include('dashboard.layouts.chat', ['topic' => $user])
            </div>

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
                        <form id="delete-many-sub-form" action="{{ route('manager.customer.subscription.delete.many') }}" method="post">
                            @csrf
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
                        </form>

                        <a class="text-info" href="{{ route('manager.order') }}?query={{ $user->phone }}">Xem các đơn hàng trước</a>
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
                    <div class="d-flex justify-content-between">
                        @can('manager.customer.modify')
                        <a id="submit" class="btn btn-primary">Cập nhật</a>
                        <a id="submit-exit" class="btn btn-primary">Lưu & thoát</a>
                        @endcan
                    </div>
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
                                <option value="{{ $item->id }}"> {{ $loop->iteration }}. {{ $item->name }}</option>
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
<form id="delete-sub-form" method="post">@csrf</form>
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
            $('#update-form').attr('action', '{{ route("manager.customer.update", ["id" => $user]) }}');
            $('#update-form').submit();
        }
    });

    $('#submit-exit').click(function () {
        if (confirm('Bạn có chắc muốn thực hiện các thay đổi này?')) {
            $('#update-form').attr('action', '{{ route("manager.customer.update.exit", ["id" => $user]) }}');
            $('#update-form').submit();
        }
    });

    $('#change-password').click(function () {
        $('#change-password-input').fadeIn();
    });

    $(document).ready(function () {
        $('.delete-sub').on('click', function () {
            let id = $(this).data('id');
            let form = $('#delete-many-sub-form');

            if (confirm('Bạn có thật sự muốn xóa gói đăng ký này?')) {
                form.submit();
            }
        });

        $('#delete-account').on('click', function () {
            let id = $(this).data('id');

            if (confirm('Bạn có muốn xóa tài khoản này không?')) {
                let form = $('#delete-sub-form');
                form.attr('action', `/manager/customer/${id}/delete`);
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
