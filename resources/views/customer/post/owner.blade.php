@extends('customer.layouts.app')

@section('content')
<div class="container">

    <div class="row bg-white p-2 shadow rounded">

        {{-- @include('customer.components.tabs') --}}


        <div class="col-md-12 mt-2">
            <div class="row">
                <div class="col-md-{{ $setting->notification ? '9' : '12' }} px-0" id="myTabContent">

                        @if ($posts->isNotEmpty())
                            @include('customer.components.posts-table', ['posts' => $posts])
                            <div class="d-flex justify-content-center">
                                {{ $posts->withQueryString()->onEachSide(1)->links() }}
                            </div>
                        @else
                            <div class="text-center">
                                <img src="{{ asset('assets/img/empty-state.jpg') }}" alt="" style="height: 100%; max-width: 100%;">
                                <h3 style="color: cadetblue;">Không có tin nào cả!</h3>
                            </div>
                        @endif
                </div>

                @if (empty($setting->notification))
                <div class="col-md-3 pr-0">
                    @if ($setting->notification)
                    <div class="p-2 text-justify" style="background-color: aliceblue; font-size: 17px; font-family; border-top: 4px solid #9ce8d9 !important;">
                        <h5 class="text-center text-uppercase">Thông Báo</h5>
                        <p style="font-size: 16px;">{{ $setting->notification }}</p>
                    </div>
                    @endif
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<!-- View post modal -->
<div class="modal fade modal-" id="post-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: #2b8cce;">
                    <i class="fa fa-file-text-o"></i>
                    <span id="modal-title">Thông tin nguồn chính chủ</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="post-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@include('customer.components.post-create')

@endsection

@push('script')
<script>
(function (window) {

$(document).ready(function () {
    $('tr[data-post-id]').on('click', function () {
        let body = $('#post-body');
        let id = $(this).data('post-id');

        body.html(`
            <div class="text-center">
                <i style="font-size: 100px" class="fa fa-refresh fa-spin" aria-hidden="true"></i>
                <p class="text-muted mt-3" style="font-size: 30px">Xin chờ chút...</p>
            </div>
        `);

        $('#post-modal').modal();

        fetch(`/post/${id}/view`)
        .then(res => {
            if (res.ok) {
                return res.text();
            }
        }).then(response => {
            body.html(response);
            registerAction();
        });
    });
});

function registerAction() {
    let id = $('#modal-post-data').data('post-id');

    $('#post-save').click(function () {
        fetchAction(`/post/${id}/action/save`);
    });

    $('#post-report').click(function () {
        if (confirm('Bạn có thực sự muốn báo môi giới tin này?')) {
            fetchAction(`/post/${id}/action/report`);
        }
    });

    $('#post-blacklist').click(function () {
        if (confirm('Bạn có muốn xóa tin này không?')) {
            fetchAction(`/post/${id}/action/blacklist`);
        }
    });
}

function fetchAction(url) {
    fetch(url)
    .then(res => {
        if (res.ok || res.status === 404) {
            return res.text();
        } else {
            alert('Có lỗi trong quá trình thực hiện, \n xin hãy thử làm mới lại trang');
        }
    }).then(text => {
        alert(text);
    })
}
}(window));
</script>
@endpush