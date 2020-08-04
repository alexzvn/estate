<div class="row" id="modal-post-data" data-post-id="{{ $post->id }}">
    <div class="col-md-8" style="font-size: medium;">

        <p><strong><i class="fa fa-hand-o-right"></i> Tiêu đề: {{ Str::ucfirst($post->title) }}</strong></p>


        <strong><i class="fa fa-hand-o-right"></i> Nội dung:</strong>
        {!! $post->content !!}

        <hr class="d-block d-md-none">
    </div>

    <div class="col-md-4 pl-0">
        <p>
            <strong>Số điện thoại: </strong>
            @isset($meta->phone->value)
            <span onclick="$(this).html($(this).data('phone'))" data-phone="{{ $meta->phone->value }}">
                <button class="btn btn-sm btn-primary">Xem SĐT</button>
            </span>
            @else
            N/a
            @endisset
            
        </p>
        <p><strong>Tỉnh/Thành phố: </strong> {{ $meta->province->province->name ?? 'N/a' }}</p>
        <p><strong>Quận/huyện: </strong> {{ $meta->district->district->name ?? 'N/a' }}</p>
        @if ($meta->commition)
        <p><strong>Hoa Hồng: </strong> {{ $meta->commition->value }}</p>
        @endif
        <p><strong>Giá khoảng: </strong> {{ format_web_price($meta->price->value) ?? 'N/a' }}</p>
        <p><strong>Ngày đăng: </strong> {{ $post->publish_at ? $post->publish_at->format('d/m/Y') : 'N/a' }}</p>

        @isset($post->report)
        <p style="color: red"><strong>Đã báo môi giới bởi: </strong> {{ Auth::id() == $post->report->user_id ? 'Bạn' : $post->report->user->name }}</p>
        @endisset

        <hr class="d-block d-md-none">

        <div class="d-flex justify-content-center">
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