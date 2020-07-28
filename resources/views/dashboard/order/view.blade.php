@extends('dashboard.app')

@push('style')
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/flatpickr/flatpickr.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/elements/tooltip.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/forms/switches.css') }}">
@endpush

@php
    $planIds = $order->plans->map(function ($e) { return $e->id; });
@endphp

@section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
        <div class="row justify-content-center">

        <div class="col-md-8">
            <div class="statbox widget box box-shadow mt-3">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>
                                @include('dashboard.layouts.back-button', ['link' => route('manager.customer.view', ['id' => $order->customer->id])])
                                Cập nhật đơn hàng
                                <span class="badge badge-{{ $order->status === $order::PAID ? 'success' : 'warning' }}">
                                    {{ $order->status === $order::PAID ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                </span>
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="update-form" action="{{ route('manager.order.update', ['id' => $order->id]) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="d-flex">
                                <label class="switch s-primary mr-2" style="margin-top: .20rem;">
                                    <input type="checkbox" id="manual" name="manual" value="1">
                                    <span class="slider round"></span>
                                </label>
                                <label>Tự chọn ngày hết hạn</label>
                            </div>
                            
                        </div>
                        <div class="form-row">
                            <div class="col-md-3">
                                <div class="form-group">
                                  <label for="activated_at">Ngày kích hoạt</label>
                                  <input type="text" class="form-control" value="{{ $order->activate_at }}" name="activated_at" id="activated_at" placeholder="Ngày hôm nay">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                  <label for="expires_at">Ngày hết hạn</label>
                                  <input type="text" class="form-control" value="{{ $order->expires_at }}" name="expires_at" id="expires_at" placeholder="Tự tính toán" disabled>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group input-group-sm">
                                    <label for="plans">Các gói đã chọn</label>
                                    <select class="form-control tagging" name="plans[]" id="plans" multiple disabled>
                                      @foreach ($plans as $item)
                                          <option value="{{ $item->id }}" {{ $plans->some($item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                      @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        

                        <div class="form-row">

                            <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                    <label for="price">Giá tiền các gói</label>
                                    <input type="text" name="price" id="price" class="form-control price" value="{{ $order->price ?? $plans->sum('price') }}" data-value="{{ $plans->sum('price') }}" disabled>
                                </div>
                            </div>

                            <div class="col-md-3" id="select-month">
                                <div class="form-group input-group-sm">
                                  <label for="expires_month">Kích hoạt / Gia hạn</label>
                                  <select class="form-control" name="expires_month" id="expires_month">
                                    @foreach (range(1, 24) as $i)
                                        <option {{ $order->month === $i ? 'selected' : '' }} value="{{ $i }}" >{{ $i }} Tháng</option>
                                    @endforeach
                                    <option value="">Không giới hạn</option>
                                  </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                  <label for="discount_type">Loại giảm giá</label>
                                  <select class="form-control" name="discount_type" id="discount_type">
                                      <option value="{{ $order::DISCOUNT_NORMAL }}">Thông thường</option>
                                    <option value="{{ $order::DISCOUNT_PERCENT  }}">Theo phần trăm</option>
                                  </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                    <label for="discount">Giảm giá</label>
                                    <input type="number" min="0" class="form-control" name="discount" id="discount" value="{{ $order->discount ?? 0 }}">
                                  </div>
                            </div>
                        </div>

                        @if (! $order->verified)
                        <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="verified" id="verified" value="1">
                              Đánh dấu là đã thanh toán
                            </label>
                        </div>
                        @endif

                        <hr>
                        <h6>Khách hàng <span class="text-info">{{ $customer->name }}</span>, số điện thoại: {{ $customer->phone }}</h6>

                        <h5 class="my-3">Tổng tiền: <span id="total-value" class="text-danger">{{ $order->after_discount_price !== null ? number_format($order->after_discount_price) : number_format($plans->sum('price')) }}đ</span></h5>

                        @if ($order->verified)
                            @can('manager.category.modify.force')
                            <button id="submit" class="btn btn-primary">Cập nhật</button>
                            @endcan
                        @else
                            <button id="submit" class="btn btn-success">Cập nhật</button>
                        @endif

                        @can('manager.order.delete')
                        <a id="delete-btn" href="javascript:void(0)" class="btn btn-danger btn-sm float-right">Xóa đơn hàng</a>
                        @endcan

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@can('manager.order.delete')
<form id="delete-form" action="{{ route('manager.order.delete', ['id' => $order->id]) }}" method="post">@csrf</form>
@endcan

@endsection

@push('script')
<script src="{{ asset('dashboard/plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('dashboard/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('dashboard/plugins/input-mask/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('dashboard/assets/js/elements/tooltip.js') }}"></script>
<script>

(function (window) {
    let f1 = flatpickr(document.getElementById('activated_at'));
    let f2 = flatpickr(document.getElementById('expires_at'));

    $('.price').inputmask({
        alias: 'currency',
        prefix: '',
        digits: 0,
        rightAlign: false
    });

    $(".tagging").select2({
        tags: true
    });

    $('#submit').click(function () {
        if (confirm('Bạn có chắc muốn thực hiện các thay đổi này?')) {
            document.getElementById('update-form').submit();
        }
    });

    $('#delete-btn').click(function () {
        if (confirm('Bạn có muốn xóa đơn hàng này không')) {
            document.getElementById('delete-form').submit();
        }
    });

    $('#change-password').click(function () {
        $('#change-password-input').fadeIn();
    });

    $('#update-form').change(function () {
        let form = {
            price: $('#price').val().replace(/,/g, '') - 0,
            expires: $('#expires_month').val() - 0,
            discount: $('#discount').val() - 0,
            discount_type: $('#discount_type').val() - 0
        };

        if ($('#manual').prop('checked')) {
            form.expires = 1;
        }

        let price = (form.expires * form.price);

        if (form.discount_type === 2) {
            price -= form.discount;
        } else {
            price -= (form.discount / 100) * price;
        }

        $('#total-value').html(new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price));
    });

    $('#manual').change(function () {
        let isManual = $('#manual').prop('checked'),
            month = $('#select-month'),
            price = $('#price'),
            expires = $('#expires_at');

        if (isManual) {
            month.hide();
            expires.prop('disabled', false);
            price.prop('disabled', false);
        } else {
            month.show();
            expires.prop('disabled', true);
            price.prop('disabled', true);
            expires.val('');
            price.val(price.data('value'));
        }
    });
}(window))
</script>
@endpush