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
                    <h4>Danh sách đen SĐT
                        @can('blacklist.phone.create')
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#create" class="btn btn-success rounded-circle"><i data-feather="plus"></i></a>
                        @endcan
                    </h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area shadow-none">

            <form id="search-form" action="" method="GET">
                <div class="row">
                    <div class="col-md-4 pl-md-0 order-first">
                        <div class="form-row">
                            <label for="phone" class="col-md-3 col-form-label text-md-right d-none d-md-block"><strong>Tìm kiếm: </strong></label>

                            <div class="col-md-9">
                                <div class="form-group input-group-sm">
                                    <input id="phone" type="text" class="form-control" name="phone" value="{{ request('phone') }}" placeholder="tìm theo SĐT">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row col-md-2 order-first">
                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                  <input type="checkbox" class="form-check-input" name="user" id="user" value="user" {{ request('user') === 'user' ? 'checked' : '' }}>
                                  Nguồn khác
                                </label>
                              </div>
                        </div>
                    </div>

                    <div class="col-md-2 pl-md-0 order-first">
                        <div class="form-group input-group-sm">
                            <select class="form-control" name="province" id="province">
                              <option value="">Chọn Tỉnh/TP</option>
                              @foreach ($provinces ?? [] as $item)
                                  <option value="{{ $item->id }}" {{ $item->id === request('province') ? 'selected' : ''}}>{{ $item->name }}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 pl-md-0 order-first">
                        <div class="form-group input-group-sm">
                            <input type="text" value="{{ request('page') }}"
                              class="form-control" name="page" id="page" aria-describedby="helpId" placeholder="Phân trang">
                            <small id="helpId" class="form-text text-muted">Phân trang</small>
                          </div>
                    </div>

                    <div class="col-md-2 pl-md-0 order-md-first order-last">
                        <button type="submit" class="btn btn-sm btn-primary mb-2">Tìm kiếm</button>
                    </div>

                </div>
            </form>
            <div class="table-responsive">
                <table id="example" class="table table-hover">
                    <thead>
                        <tr>
                            <th class="checkbox-column">
                                <div class="custom-control custom-checkbox checkbox-primary">
                                  <input type="checkbox" class="custom-control-input" id="check-all">
                                  <label class="custom-control-label" for="check-all"></label>
                                </div>
                            </th>
                            <th>Số điện thoại</th>
                            <th>Người thêm</th>
                            <th>Thông tin</th>
                            <th>Xuất excel</th>
                            <th>Ngày thêm</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach ($blacklist as $phone)
                        <tr>
                            <td class="checkbox-column">
                                <div class="custom-control custom-checkbox checkbox-primary">
                                  <input type="checkbox" class="custom-control-input phone" id="phone-{{ $loop->index }}" name="phone[]" value="{{ $phone->phone }}">
                                  <label class="custom-control-label" for="phone-{{ $loop->index }}">{{ $loop->index }}</label>
                                </div>
                            </td>
                            <td>
                                <span class="text-secondary font-weight-bold">
                                    {{ $phone->phone }} 
                                    <span class="text-muted">
                                        ({{ $phone->posts->count() }})
                                    </span>
                                </span>
                            </td>
                            <td>{{ $phone->adder->name ?? '' }} @empty($phone->adder->name) <strong class="text-info">{{ $phone->source ?? 'API' }}</strong> @endEmpty</td>
                            <td>
                                <strong>
                                    @isset($phone->name)
                                        <p class="m-0">{{ $phone->name }}</p>
                                    @endisset
                                    @isset($phone->province)
                                        <p class="m-0">{{ $phone->province->name }}</p>
                                    @endisset
                                    @isset($phone->category)
                                        <p class="m-0">{{ $phone->category }}</p>
                                    @endisset
                                    @isset ($phone->url)
                                        <a class="text-info" target="_blank" href="{{ $phone->url }}">Link bài gốc</a>
                                    @endisset
                                </strong>
                            </td>
                            <td>{{ $phone->export_count }}</td>
                            <td>{{ $phone->created_at ? $phone->created_at->format('d/m/Y H:i:s') : 'n/a' }}</td>
                            <td>
                                <div class="">
                                    @can('blacklist.phone.sms')
                                    <button type="button" class="btn btn-primary sms" data-id="{{ $phone->phone }}"><small>{{ $phone->sms_count }}</small> SMS</button>
                                    @endcan

                                    @can('blacklist.phone.delete')
                                    <button type="button" class="btn btn-danger delete" data-id="{{ $phone->phone }}">Xóa</button>
                                    @endcan

                                    @can('manager.customer.create')
                                    @empty($phone->user)
                                    <a target="_blank" href="{{ route('manager.customer.create') . "?phone=$phone->phone&name=$phone->name" }}" type="button" class="btn btn-success">Tạo TK</a>
                                    @endempty
                                    @endcan

                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Số điện thoại</th>
                            <th>Ngày thêm</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>

                <div class="d-flex mb-4">
                    <div class="btn-group mr-2" role="group">
                        <button id="btndefault" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Hành động 
                            <i data-feather="chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btndefault">
                            <a id="send-sms" href="javascript:void(0)">Gửi tin nhắn</a>
                        </div>
                    </div>

                    <a href="{{ route('manager.blacklist.export') }}?page={{ request('page') }}" type="button" class="btn btn-success">Xuất excel</a>
                </div>

            </div>
            <span class="text-muted">Tìm thấy {{ $blacklist->total() }} kết quả</span>

            <div class="d-flex justify-content-center">
                {{ $blacklist->onEachSide(0)->withQueryString()->render() }}
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('manager.blacklist.phone.store') }}" method="post"> @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="phone">Số điện thoại</label>
                      <input type="text"
                        class="form-control" name="phone" id="phone" placeholder="Số điện thoại">
                    </div>
                    {{-- <div class="form-group">
                      <label for="note">Ghi chú</label>
                      <textarea class="form-control" name="note" id="note" rows="3" placeholder="Ghi chú gì đó..."></textarea>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">Thêm</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="fetch-sms" tabindex="-1" role="dialog" aria-labelledby="Sms" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lịch sử nhắn SMS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="fetch-sms-form">
                    @csrf

                    <div class="form-group">
                        <p>Tổng số lần: <strong id="sms-count"></strong> </p>
                    </div>

                    <div class="form-group">
                      <label>Lịch sử nhắn: </label>
                      <textarea id="sms-history" class="form-control w-100" readonly rows="3"></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">Thêm lần SMS</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<form id="delete-form" action="" method="POST">@csrf</form>
@endsection

@push('script')
<script>

    $('#check-all').click(function () {
        let checked = $('#check-all').prop('checked');
        $('.phone').prop('checked', checked);
    });

    $('#send-sms').click(() => {
        const phones = []

        $('.phone').each(function () {
            phones.push($(this).val())
        })

        const uri = phones.map((phone) => `recipients[]=${phone}`).join('&');

        location.href = "{{ route('manager.sms.template') }}?" + uri
    })

    $(document).ready(function() {

        $('.note').on('change', function () {
            let id = $(this).data('id');

            fetch(`/manager/blacklist/phone/${id}/update`, {
                headers: {
                    "Content-type": "application/x-www-form-urlencoded"
                },
                method: 'post',
                body: '_token=' + csrf() + '&note=' + $(this).val()
            })
        });

        $('.delete').on('click', function () {
            let id = $(this).data('id');

            let form = $('#delete-form');

            form.attr('action', `/manager/blacklist/phone/${id}/delete`);

            form.submit();
        });

        $('.sms').on('click', function () {
            const id = $(this).data('id');

            fetch(`/manager/blacklist/phone/${id}/sms`)
                .then(res => res.json())
                .then(data => {
                    let i = 0;

                    const histories = data.history.map(e => ++i + '. ' + e).join('\n');

                    $('#sms-count').html(data.count);
                    $('#sms-history').val(histories);
                }).catch(() => {
                    Snackbar.show({
                        text: 'Danger',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                        text: 'Có lỗi trong quá trình lấy dữ liệu',
                        pos: 'bottom-right',
                        duration: 5000,
                        showAction: false
                    });
                });

            $('#fetch-sms-form').attr('action', `/manager/blacklist/phone/${id}/sms/increase`);

            $('#fetch-sms').modal();
        });
    });

</script>
@endpush
