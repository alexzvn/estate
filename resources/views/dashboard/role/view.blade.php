@extends('dashboard.app')

@section('content')
<div class="col-lg-12">
    <form class="row" action="{{ route('manager.role.update', ['id' => $role->id]) }}" method="post">@csrf

        <div class="col-md-4">

            <div class="statbox widget box mb-3">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Cập nhật vai trò</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">

                    <div class="form-group">
                      <label for="name">Tên vai trò</label>
                      <input type="text"
                        class="form-control" name="name" id="name" value="{{ $role->name }}">
                    </div>

                    <div class="form-check mb-3">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="for_customer" id="for_customer" value="1" {{ $role->customer ? 'checked' : '' }}>
                        Vai trò này cho khách hàng
                      </label>
                    </div>

                    

                    @can('manager.role.delete')
                    <button id="delete" type="button" class="btn btn-outline-danger float-left">Xóa</button>
                    @endcan
                    @can('manager.role.modify')
                    <button type="submit" class="btn btn-primary float-right">Cập nhật</button>
                    @endcan
                </div>
            </div>

        </div>

        <div class="col-md-8">

            <div class="statbox widget box shadow-none mb-3">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Danh sách quyền Quản Lý</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                   <div class="form-row">

                        @foreach ($groups as $group)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">

                            <p class="text-info m-0"><strong>{{ $group->name }}</strong></p>

                            <div class="ml-3">
                                @foreach ($group->permissions as $perm)
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="permissions[]" id="permissions"
                                    value="{{ $perm->id }}" {{ $role->permissions->whereIn(id, $perm->id)->count() ? 'checked' : '' }}>
                                    {{ $perm->display_name }}
                                  </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach


                   </div>
                </div>
            </div>

        </div>
    </form>
</div>

<form id="delete-form" action="{{ route('manager.role.delete', ['id' => $role->id]) }}" class="d-none" method="post">
@csrf
</form>
@endsection

@push('script')
<script>
$('#delete').click(function () {
    if (confirm('Bạn thực sự muốn xóa vai trò này?')) {
        document.getElementById('delete-form').submit();
    }
});
</script>
@endpush