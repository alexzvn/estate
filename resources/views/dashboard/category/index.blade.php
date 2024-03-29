@extends('dashboard.app')

@section('content')

@can('manager.category.create')

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
                        <option style="font-weight: bold;" value="{{ $item->id }}">{{ $item->name }}</option>
                       @if ($item->children)
                           @foreach ($item->children as $item)
                           <option value="{{ $item->id }}">{{ $item->name }}</option>
                           @endforeach
                       @endif
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
@endcan

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
                    @foreach ($categories as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight: bold;"> <a href="{{ route('manager.category.view', ['id' => $item->id]) }}">{{ $item->name }}</a></td>
                            <td>{{ $item->description ?? 'N/A' }}</td>
                        </tr>
                        @if ($item->children)
                            @foreach ($item->children as $item)
                            <tr>
                                <td></td>
                                <td><i data-feather="corner-down-right"></i> <a href="{{ route('manager.category.view', ['id' => $item->id]) }}">{{ $item->name }}</a></td>
                                <td>{{ $item->description ?? 'N/A' }}</td>
                            </tr>
                            @if ($item->children)
                                @foreach ($item->children as $item)
                                <tr>
                                    <td></td>
                                    <td><i class="ml-4" data-feather="corner-down-right"></i> <a href="{{ route('manager.category.view', ['id' => $item->id]) }}">{{ $item->name }}</a></td>
                                    <td>{{ $item->description ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            @endif
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
