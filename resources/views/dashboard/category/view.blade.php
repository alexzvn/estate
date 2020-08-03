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
                                @include('dashboard.layouts.back-button', ['link' => route('manager.category')])
                                Sửa danh mục
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                   <form action="{{ route('manager.category.update', ['id' => $category->id]) }}" method="post">
                    @csrf
                       <div class="form-group input-group-sm">
                         <label for="name">Tên danh mục</label>
                         <input type="text"
                           class="form-control" value="{{ $category->name }}" name="name" id="name" placeholder="Tên của danh mục" required>
                       </div>
                       <div class="form-group">
                           <label for="parent">Danh mục cha</label>
                           <select class="custom-select" name="parent" id="parent">
                               <option value="" selected>--- Chọn ---</option>
                               @foreach ($categories as $item)
        
                                @if ($category->id !== $item->id)
                                <option style="font-weight: bold;" value="{{ $item->id }}" {{ $parentId == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endif

                               @if ($item->children)
                                   @foreach ($item->children as $item)
                                   @if ($category->id !== $item->id) 
                                   <option value="{{ $item->id }}" {{ $parentId == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                   @endif
                                   
                                   @endforeach
                               @endif
                               @endforeach
                           </select>
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

<form id="delete-form" class="d-none" action="{{ route('manager.category.delete', ['id' => $category->id]) }}" method="post">@csrf</form>
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
