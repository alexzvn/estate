<div class="row" id="modal-post-data" data-post-id="{{ $post->id }}">
    <div class="col-md-8" style="font-size: medium;">

        <p class="tw-mb-2"><strong><i class="fa fa-hand-o-right"></i> Tiêu đề: {{ Str::ucfirst($post->title) }}</strong></p>


        <strong><i class="fa fa-hand-o-right"></i> Nội dung:</strong>
        {!! $post->content !!}

        @isset($post->extra)
        <strong class="tw-mt-2">Thông tin thêm: </strong>

            @isset($post->extra['groupName'])
            <br>Nhóm FB: <a class="tw-text-blue-600 hover:tw-to-blue-400" target="_blank" href="{{ $post->extra['groupUrl'] }}">{{ $post->extra['groupName'] }}</a>
            @endisset

            @isset($post->extra['authorName'])
            <br>Người đăng: <a class="tw-text-blue-600 hover:tw-to-blue-400" target="_blank" href="{{ $post->extra['authorUrl'] }}">{{ $post->extra['authorName'] }}</a>
            @endisset

            @isset($post->extra['originalUrl'])
            <br><a class="tw-text-blue-600 hover:tw-to-blue-400" target="_blank" href="{{ $post->extra['originalUrl'] }}">Link bài viết gốc</a>
            @endisset

        @endisset

        <hr class="d-block d-md-none tw-my-2">
    </div>

    <div class="col-md-4 pl-lg-0">
        <p>
            <strong>Số điện thoại: </strong>
            @isset($post->phone)
            <span onclick="$(this).html(`<a class='tw-text-blue-600 hover:tw-to-blue-400' href='tel:${$(this).data('phone')}'>${$(this).data('phone')}</a>`)" data-phone="{{ $post->phone }}">
                <button class="btn btn-sm btn-success">Xem SĐT</button>
            </span>
            @else
            Liên hệ
            @endisset
        </p>
        <p class="tw-mt-1"><strong>Giá khoảng: </strong> {{ format_web_price($post->price) ?? 'Không rõ' }}</p>
        @if ($post->commission)
        <p class="tw-mt-1"><strong>Hoa Hồng: </strong> {{ $post->commission }}</p>
        @endif
        <p class="tw-mt-1"><strong>Quận/huyện: </strong> {{ $post->district->name ?? 'Không rõ' }}</p>
        <p class="tw-mt-1"><strong>Tỉnh/Thành phố: </strong> {{ $post->province->name ?? 'Không rõ' }}</p>
        @isset($post->categories[0])
        <p class="tw-mt-1"><strong>Danh mục: </strong> {{ $post->categories[0]->name }}</p>
        @endisset
        
        <p class="tw-mt-1"><strong>Ngày đăng: </strong> {{ $post->publish_at ? $post->publish_at->format('d/m/Y') : 'Không rõ' }}</p>

        @isset($post->report)
        <p style="color: red"><strong>Đã báo môi giới bởi: </strong> {{ Auth::id() == $post->report->user_id ? 'Bạn' : $post->report->user->name }}</p>
        @endisset

        <hr class="d-block d-md-none tw-my-2">

        <div class="d-flex justify-content-center tw-mt-2">
            @if (in_array($post->id, $customer->post_save_ids ??[]))
            <button id="post-save" type="button" class="btn btn-sm btn-primary mr-2">Bỏ lưu</button>
            @else
            <button id="post-save" type="button" class="btn btn-sm btn-outline-primary mr-2">Lưu tin</button>
            @endif

            @empty($post->report)
            <button id="post-report" type="button" class="btn btn-sm btn-warning mr-2">Báo môi giới</button>
            @endempty

            @if (in_array($post->id, $customer->post_blacklist_ids ??[]))
            <button id="post-blacklist" type="button" class="btn btn-sm btn-danger">Khôi phục</button>
            @else
            <button id="post-blacklist" type="button" class="btn btn-sm btn-outline-danger">Xóa tin</button>
            @endif
        </div>
    </div>
</div>
