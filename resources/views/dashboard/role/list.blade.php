@extends('dashboard.app')

@section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>
                        Danh sách vai trò 
                        <button class="btn btn-success rounded-circle"><i data-feather="plus"></i></button>
                    </h4>
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
@endsection

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