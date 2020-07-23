@extends('dashboard.app')

@section('content')

<div class="col-lg-12">
    <form class="row" action="{{ route('manager.role.store') }}" method="post">@csrf

        <div class="col-md-4">

            <div class="statbox widget box mb-3">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Tạo vai trò trên trang</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">

                    <div class="form-group">
                      <label for="name">Tên vai trò</label>
                      <input type="text"
                        class="form-control" name="name" id="name">
                    </div>

                    <div class="form-check mb-3">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="for_customer" id="for_customer" value="1">
                        Vai trò này cho khách hàng
                      </label>
                    </div>

                    @can('manager.role.modify')
                    <button type="submit" class="btn btn-primary float-right">Tạo mới</button>
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
                                    <input type="checkbox" class="form-check-input" name="permissions[]" id="permissions" value="{{ $perm->id }}">
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

@endsection

{{-- <div class="statbox widget box shadow-none mb-3">
    <div class="widget-header">
        <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">

            </div>
        </div>
    </div>
    <div class="widget-content widget-content-area">
       
    </div>
</div> --}}