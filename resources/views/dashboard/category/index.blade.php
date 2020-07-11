@extends('dashboard.app')

@section('content')
<div class="col-md-5">
    <div class="statbox widget box shadow-none mb-3">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>Thêm danh mục mới</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
           <form action="{{ route('manager.category.store') }}" method="post">
            @csrf
               <div class="form-group input-group-sm">
                 <label for="name">Tên danh mục</label>
                 <input type="text"
                   class="form-control" value="@error('name') {{ $message }} @enderror" name="name" id="name" placeholder="Tên của danh mục" required>
               </div>
               <div class="form-group">
                   <label for="parent">Danh mục cha</label>
                   <select class="custom-select" name="parent" id="parent">
                       <option value="" selected>--- Chọn ---</option>
                       @foreach ($categories as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                       @endforeach
                   </select>
               </div>
               <div class="form-group">
                 <label for="description">Mô tả</label>
                 <textarea class="form-control" name="description" id="description" rows="3" placeholder="Mô tả về danh mục này?"></textarea>
               </div>

               <div class="text-right">
                    <button type="submit" class="btn btn-success">Thêm mới</button>
               </div>

           </form>
        </div>
    </div>
</div>

<div class="col-md-7">
    <div class="statbox widget box box-shadow mb-3">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>Danh sách danh mục</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <table class="table table-hover table-light mb-4">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $cat)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $cat->name }}</td>
                            <td>{{ $cat->description ?? 'N/A' }}</td>
                        </tr>
                        @if ($cat->children)
                            @foreach ($cat->children as $item)
                            <tr>
                                <td></td>
                                <td><i data-feather="corner-down-right"></i> {{ $item->name }}</td>
                                <td>{{ $item->description ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

{{-- @section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>Danh sách danh mục hiện có</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-hover table-light mb-4">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Tên</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                        <tr>
                            <td class="text-center" >{{ $loop->index }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <i class="role-remove t-icon t-hover-icon" data-feather="trash-2" data-id="{{ $role->id }}"></i>
                                <i class="role-edit t-icon t-hover-icon" data-feather="edit" data-id="{{ $role->id }}"></i>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection --}}

@push('script')
<script>
    $(document).ready(() => {
        $('.role-remove').each(function () {
            var $this = $(this);
            $this.on("click", function () {
                alert($(this).data('id'));
            });
        });
    });
</script>
@endpush
