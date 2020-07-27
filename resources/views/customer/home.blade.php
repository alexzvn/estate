@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row bg-white p-2 shadow rounded">
        <div class="col-md-12 px-0">
            <div class="border rounded-top">
                <ul class="nav nav-tabs nav-custom-tabs mx-3" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#section-1" role="tab">Mua Bán</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#section-2" role="tab">Cần Thuê</a>
                    </li>
                    <li class="nav-item d-none">
                      <a class="nav-link" id="contact-tab" data-toggle="tab" href="#section-3" role="tab">Thị Trường</a>
                    </li>
                </ul>
                <div class="border-top p-3" style="background-color: #f7f7f7;">
                    <form action="" method="GET">

                        <div class="row">
                            <div class="col-md-5 pl-0">
                                <div class="form-row">
                                    <label for="query" class="col-md-3 col-form-label text-md-right"><strong>Tìm kiếm</strong></label>

                                    <div class="col-md-9">
                                        <input id="query" type="text" class="form-control" name="query" value="{{ request('query') }}" placeholder="Nhập từ khóa tìm kiếm...">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 pl-0">
                                <div class="form-group">
                                  <select class="form-control" name="categories" id="categories">
                                    <option value="">Chọn chuyên mục</option>
                                    @foreach ($categories as $item)
                                    <option style="color: black; font-weight: bold;" disabled>{{ $item->name }}</option>
                                        @foreach ($item->children ?? [] as $item)
                                        <option value="{{ $item->id }}" {{ $item->id === request('categories') ? 'selected' : ''}}>{{ $item->name }}</option>
                                        @endforeach
                                    @endforeach
                                  </select>
                                </div>
                            </div>

                            <div class="col-md-2 pl-0">
                                <div class="form-group">
                                    <select class="form-control" name="province" id="province">
                                      <option value="">Chọn Tỉnh/TP</option>
                                      @foreach ($provinces as $item)
                                          <option value="{{ $item->id }}" {{ $item->id === request('province') ? 'selected' : ''}}>{{ $item->name }}</option>
                                      @endforeach
                                    </select>
                                  </div>
                            </div>

                            <div class="col-md-3 pl-0">
                                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                                <a class="btn btn-link" href="#" role="button">Tìm kiếm nâng cao <i class="fa fa-caret-down" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-12 mt-2">
            <div class="row">
                <div class="col-md-{{ $setting->notification ? '9' : '12' }} px-0" id="myTabContent">
                    <div class="tab-content" >
                        <div class="tab-pane fade show active" id="section-1" role="tabpanel">
                            @include('customer.components.posts-table', ['posts' => $sellPosts])
                            {{ $sellPosts->appends($_GET)->render() }}
                        </div>
                        <div class="tab-pane fade" id="section-2" role="tabpanel">
                            @include('customer.components.posts-table', ['posts' => $rentPosts])
                            {{ $rentPosts->appends($_GET)->render() }}
                        </div>
                        {{-- <div class="tab-pane fade" id="section-3" role="tabpanel">
                            @include('customer.components.posts-table', compact('posts'))
                            {{ $posts->appends($_GET)->render() }}
                        </div> --}}
                    </div>
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

<!-- Modal -->
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
@endsection

@push('script')
<script>
$(document).ready(function () {
    $('tr[data-post-id]').on('click', function () {
        let body = $('#post-body');
        let id = $(this).data('post-id');

        body.html('');
        $('#post-modal').modal();

        fetch(`/post/${id}/view`)
        .then(res => {
            if (res.ok) {
                return res.text();
            }
        }).then(response => {
            body.html(response);
        });
    });
});
</script>
@endpush

@push('style')
<style>
.nav-custom-tabs {
    border: 0;
}

.nav-custom-tabs .nav-item .nav-link {
    text-transform: uppercase;
    background: none;
    color: gray;
    border: 0;
}

.nav-custom-tabs .nav-item .nav-link.active {
    color: black;
    font-weight: bolder;
    border-bottom: solid 3px #3e92cc;
}

</style>
@endpush