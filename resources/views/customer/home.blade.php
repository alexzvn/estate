@extends('customer.layouts.app')

@section('content')
<div class="container">

    <div class="row bg-white p-2 shadow rounded">
        
        @include('customer.components.tabs')

        <div class="col-md-12 mt-2">
            <div class="row">
                <div class="col-md-12 px-0" id="myTabContent">
                        @if (request()->user()->subscriptions->isEmpty() || (isset($canAccess) && $canAccess === false))
                        <div class="text-center">
                            <h3 class="my-3" style="color: cadetblue;">Có vẻ bạn chưa đăng ký gói tin nào. <br> Hãy liên hệ hotline để đăng ký và bắt đầu xem tin nhé!</h3>
                            <img src="{{ asset('assets/img/empty-state.jpg') }}?ver=1" alt="" style="height: 100%; max-width: 100%;">
                        </div>
                        @else
                            @include('customer.components.posts-table', ['posts' => $posts])
                            <div class="d-flex justify-content-center">
                                {{ $posts->withQueryString()->onEachSide(1)->links('customer.layouts.paginate') }}
                            </div>
                        @endif

                </div>
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
    $('td[data-post-id]').on('click', function () {
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
            $('#reported').html('Đã báo môi giới bởi: Bạn');
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