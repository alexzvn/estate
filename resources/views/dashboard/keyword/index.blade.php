@extends('dashboard.app')

@push('style')
<link rel="stylesheet" href="{{ asset('dashboard/assets/css/tables/table-basic.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/plugins/table/datatable/dt-global_style.css') }}">
@endpush

@section('content')
<div class="col-md-12">
    <div class="statbox widget box shadow-none rounded mb-3">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>Danh sách từ khóa
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#create" class="btn btn-success rounded-circle"><i data-feather="plus"></i></a>
                    </h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area shadow-none">

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                STT
                            </th>
                            <th>Từ khóa</th>
                            <th>Số tin</th>
                            <th>Tin liên quan</th>
                            <th>Ngày thêm</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($keywords as $keyword)
                        <tr>
                            <td class="text-center">
                                {{ $loop->index + 1 }}
                            </td>
                            <td>
                                @if ($keyword->linear)
                                    <span class="badge badge-info">Tuyến tính</span>
                                @endif
                                {{ $keyword->key }}
                            </td>
                            <td>{{ number_format($keyword->count) }}</td>
                            <td>{{ number_format($keyword->relative) }}</td>
                            <td>{{ $keyword->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <ul class="table-controls">
                                    <li>
                                        <a href="{{ route('manager.keyword.view', $keyword) }}">
                                            <i data-feather="eye"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="delete" href="javascript:void(0)" data-id="{{ $keyword->id }}">
                                            <i data-feather="trash-2"></i>
                                        </a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Từ khóa</th>
                            <th>Số tin</th>
                            <th>Tin liên quan</th>
                            <th>Ngày thêm</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>

            </div>
            <small class="text-muted">Tin liên quan tính bao gồm cả số tin</small> <br>
            <span class="text-muted">Tìm thấy {{ $keywords->total() }} kết quả</span>

            <div class="d-flex justify-content-center">
                {{ $keywords->onEachSide(0)->withQueryString()->render() }}
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('manager.keyword.store') }}" method="post"> @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="key">Từ khóa</label>
                      <input type="text"
                        class="form-control" name="key" id="key" placeholder="Thêm từ khóa">
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="linear" value="0" checked>
                        Khớp cụm từ
                      </label>
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="linear" value="1">
                        Khớp bất kỳ
                      </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">Thêm</button>
                </div>
            </div>
        </form>
    </div>
</div>

<form id="delete-form" action="" method="POST">@csrf</form>
@endsection

@push('script')
<script>

    $(document).ready(function() {
        $('.delete').on('click', function () {
            if (! confirm('Xác nhận xóa?')) return;

            let id = $(this).data('id');

            let form = $('#delete-form');

            form.attr('action', `/manager/keywords/${id}/delete`);

            form.submit();
        });
    });

</script>
@endpush
