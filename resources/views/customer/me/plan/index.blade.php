@extends('customer.me.components.app')

@section('main-content')
<div class="card">
    <div class="card-body">

        <h1 class="tw-text-xl tw-font-bold tw-mb-4">Đăng ký & gia hạn</h1>

        @if ($order = session('success'))
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            <div class="tw-text-base">
                <strong>Cảm ơn bạn đã đăng ký !</strong>
                <p>Xin hãy thanh toán trước để chúng tôi giúp bạn kích hoạt bằng cách chuyển khoản theo thông tin ở dưới.</p>

                <table class="tw-mt-2">
                    <tr>
                        <td colspan="2" class="tw-font-bold">Ngân hàng Vietcombank</td>
                    </tr>
                    <tr>
                        <td>Chủ tài khoản:</td>
                        <td>TRAN THI THUY</td>
                    </tr>
                    <tr>
                        <td>Số tài khoản:</td>
                        <td class="tw-font-bold">1017290966</td>
                    </tr>
                    <tr>
                        <td>Khoản tiền</td>
                        <td class="tw-font-bold">{{ number_format($order->sumMonthPrice()) }} đ</td>
                    </tr>
                    <tr>
                        <td>Nội dung:</td>
                        <td class="tw-font-bold">dang ky {{ user()->phone }}</td>
                    </tr>
                </table>
            </div>
        </div>
        @endif

        <form action="{{ route('customer.self.orders.register') }}" method="post"> @csrf
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên gói</th>
                        <th>Giá theo tháng</th>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plans as $plan)
                    <tr>
                        <td><input class="plan" type="checkbox" name="plans[]" value="{{ $plan->id }}" data-price="{{ $plan->price }}"></td>
                        <td>{{ $plan->name }}</td>
                        <td>{{ number_format($plan->price) }} đ</td>
                        <td><a class="tw-text-blue-600 collapser" href="javascript:void(0)" data-target="#collapse-{{ $loop->index }}">Chi tiết</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @foreach ($plans as $plan)
            <div class="collapse" id="collapse-{{ $loop->index }}">
                <div class="card card-body">
                    <h3 class="tw-text-base tw-font-semibold">{{ $plan->name }}</h3>
                    <div class="form-row">
    
                        <div class="col-md-4 col-sm-6 mb-4">
    
                            <p class="text-info m-0"><strong>Danh mục được truy cập</strong></p>
    
                            <div class="ml-3">
                                @foreach ($categories as $item)
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" id="categories" disabled
                                        value="{{ $item->id }}" {{ collect($plan->categories)->where('id', $item->id)->isNotEmpty() ? 'checked' : '' }}>
                                        {{ $item->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
    
                        <div class="col-md-4 col-sm-6 mb-4">
                            <p class="text-info m-0"><strong>Loại tin được truy cập</strong></p>
                            <div class="ml-3">
                                @foreach ($postTypes as $name)
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" disabled
                                        id="post_type" value="{{ $name }}" {{ in_array($name, $plan->types ?? []) ? 'checked': '' }}>
                                        {{ $name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
    
                        <div class="col-md-4 col-sm-6 mb-4">
                        <p class="text-info m-0"><strong>Thành phố được truy cập:</strong></p>
                        @foreach ($provinces as $item)
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="provinces" disabled
                                value="{{ $item->id }}" {{ collect($plan->provinces)->where('id', $item->id)->isNotEmpty() ? 'checked' : '' }}>
                                {{ $item->name }}
                            </label>
                        </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="form-row">
                <div class="col-sm-3 col-md-3">
                    <div class="form-group">
                      <select class="form-control" name="month" id="month">
                        @foreach (range(1, 12) as $item)
                        <option value="{{ $item }}">{{ "$item tháng" }}</option>
                        @endforeach
                      </select>
                      <label class="tw-ml-1 tw-mt-1" for="month">Tổng: <span class="tw-text-green-600 tw-font-semibold" id="display-price">0 đ</span></label>
                    </div>
                </div>
                <div class="col-sm-2 col-md-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success tw-block">Đăng ký</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('script')
<script>
(function (window) {

    const formatter = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })

    const testPrice = () => {
        let total = 0

        $('.plan:checked').each(function () {
            total += $(this).data('price') * $('#month').val()
        });

        $('#display-price').html(formatter.format(total))
    }

    const maxPlans = '{{ $plans->count() }}' - 0

    $('.collapser').on('click', function () {
        for (const index of Array(maxPlans).keys()) {
            $(`#collapse-${index}`).collapse('hide')
        }

        $($(this).data('target')).collapse('show')
    })

    $('#month').change(testPrice)
    $('.plan').change(testPrice)

    testPrice()

}(window))
</script>
@endpush
