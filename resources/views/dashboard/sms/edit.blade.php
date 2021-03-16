@extends('dashboard.app')

@php
    $parentId = $category->parent && $category->parent->id ? $category->parent->id : null;
@endphp

@section('content')
<div class="col-lg-12">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">
            <div class="statbox widget box shadow-none mb-3">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>
                                
                                Sửa mẫu sms
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                   <form action="" method="post">
                    @csrf
                       <div class="form-group input-group-sm">
                         <label for="name">Tên danh mục</label>
                         <input type="text"
                           class="form-control" value="" name="name" id="name" placeholder="Tên của danh mục" required>
                       </div>
                       <div class="form-group">
                         <label for="description">Mô tả</label>
                         <textarea class="form-control" name="description" id="description" rows="3" placeholder="Mô tả về danh mục này?"></textarea>
                       </div>

                       
                       <div>
                            @can('manager.category.modify')
                            <button id="delete" type="button" class="btn btn-danger float-left">Xóa</button>
                            @endcan
                            @can('manager.category.delete')
                            <button type="submit" class="btn btn-primary float-right">Cập nhật</button>
                            @endcan
                       </div>

                   </form>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="delete-form" class="d-none" action="" method="post">@csrf</form>
@endsection

@push('script')
<script>
$('#delete').click(function (e) {
    if (confirm('Bạn có muốn xóa danh mục này?')) {
        document.getElementById('delete-form').submit();
    }
});
</script>
@endpush
