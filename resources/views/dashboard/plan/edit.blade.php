@extends('dashboard.app')

@section('content')
@can('manager.plan.view')
<div class="col-sm-12">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="statbox widget box shadow mb-3">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Thêm gói mới</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                   <form action="{{ route('manager.plan.update', ['id' => $plan->id]) }}" method="post">
                        @csrf
        
                        <div class="form-row">
                            <div class="col-md-7">
                                <div class="form-group input-group-sm">
                                    <label for="name">Tên gói</label>
                                    <input type="text" name="name" id="name" value="{{ $plan->name }}" class="form-control" placeholder="Tên gói đăng ký" required>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group input-group-sm">
                                <label for="price">Giá tiền</label>
                                <input type="text" class="form-control" name="price" id="price" value="{{ $plan->price ?? 0 }}">
                                <small class="form-text text-muted">Giá tiền cho 1 tháng đăng ký</small>
                                </div>
                            </div>
                        </div>
        
                        <h5 class="mb-3">Chọn các tính năng</h5>

                        <div class="form-check mb-3">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="renewable" id="renewable" value="true" @if($plan->renewable) checked @endif>
                            Khách tự gia hạn
                          </label>
                        </div>

                        <div class="form-row">

                            <div class="col-md-4 col-sm-6 mb-4">

                                <p class="text-info m-0"><strong>Danh mục được truy cập</strong></p>

                                <div class="ml-3">
                                    @foreach ($categories as $item)
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="categories[]" id="categories"
                                            value="{{ $item->id }}" {{ in_array($item->id, $plan->categories->toArray())? 'checked' : '' }}>
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
                                            <input type="checkbox" class="form-check-input" name="post_type[]"
                                            id="post_type" value="{{ $name }}" {{ in_array($name, $plan->types->toArray()) ? 'checked': '' }}>
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
                                    <input type="checkbox" class="form-check-input" name="provinces[]" id="provinces"
                                    value="{{ $item->id }}" {{ in_array($item->id, $plan->provinces->toArray()) ? 'checked' : '' }}>
                                    {{ $item->name }}
                                </label>
                            </div>
                            @endforeach
                            </div>
                        </div>
        
                        <div class="">
                            @can('manager.plan.delete')
                            <a href="javascript:void(0)" id="submit" class="btn btn-outline-danger">Xóa</a>
                            @endcan
        
                            @can('manager.plan.modify')
                            <button type="submit" class="btn btn-primary float-right">Cập nhật gói đăng ký</button>
                            @endcan
                        </div>
                   </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan

@can('manager.plan.delete')
<form id="delete-form" action="{{ route('manager.plan.delete', ['id' => $plan->id]) }}" class="d-none" method="post">@csrf</form>
@endcan

@endsection

@push('script')
<script src="{{ asset('dashboard/plugins/input-mask/jquery.inputmask.bundle.min.js') }}"></script>
<script>

$('#price').inputmask({
    alias: 'currency',
    prefix: '',
    digits: 0,
    rightAlign: false
});

$('#submit').click(function () {

    if (confirm("Bạn có chắc muốn xóa gói đăng ký này chứ.\nNó có thể ảnh hưởng tới người đang sử dụng gói này.\n\nHãy cẩn thận!")) {
        $('#delete-form').submit();
    }
});

</script>
@endpush
