@extends('dashboard.app')

@section('content')

@can('manager.plan.create')

<div class="col-md-7">
    <div class="statbox widget box shadow-none mb-3">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>Thêm gói mới</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
           <form action="{{ route('manager.plan.store') }}" method="post">
                @csrf

                <div class="form-row">
                    <div class="col-md-7">
                        <div class="form-group input-group-sm">
                            <label for="name">Tên gói</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Tên gói đăng ký" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group input-group-sm">
                        <label for="price">Giá tiền</label>
                        <input type="text" class="form-control" name="price" id="price">
                        <small class="form-text text-muted">Giá tiền cho 1 tháng đăng ký</small>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3">Chọn các tính năng</h5>

                <div class="form-check mb-3">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" name="renewable" id="renewable" value="true" checked>
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
                                    <input type="checkbox" class="form-check-input" name="categories[]" id="categories" value="{{ $item->id }}">
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
                                    <input type="checkbox" class="form-check-input" name="post_type[]" id="post_type" value="{{ $name }}">
                                    {{ \App\Enums\PostType::getDescription($name) }}
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
                            <input type="checkbox" class="form-check-input" name="provinces[]" id="provinces" value="{{ $item->id }}">
                            {{ $item->name }}
                        </label>
                    </div>
                    @endforeach
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Tạo gói đăng ký mới</button>
                </div>
           </form>
        </div>
    </div>
</div>
@endcan

<div class="col-md-5">
    <div class="statbox widget box box-shadow mb-3">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>Danh sách gói tính năng có sẵn</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <table class="table table-hover table-light mb-4">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Tên gói</th>
                        <th>Giá</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plans as $item)
                    <tr>
                        <td>{{ $loop->iteration +1 }}</td>
                        <td>
                            {{ $item->name }}

                            @if ($item->renewable)
                            <span class="badge badge-info">Khách</span>
                            @endif
                        </td>
                        <td>{{ number_format($item->price) }}</td>
                        <td>
                            <a href="{{ route('manager.plan.view', ['id' => $item->id]) }}">
                                <i class="t-icon t-hover-icon" data-feather="edit" data-id="{{ $item->id }}"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
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

</script>
@endpush
