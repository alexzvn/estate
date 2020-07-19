<div class="row">
    <div class="col-md-8" style="font-size: medium;">
        {!! $post->content !!}
    </div>
    <div class="col-md-4">
        <p><strong>Tỉnh/Thành phố: </strong> {{ $meta->province->province->name ?? 'N/a' }}</p>
        <p><strong>Quận/huyện: </strong> {{ $meta->district->district->name ?? 'N/a' }}</p>
        @if ($meta->commition)
        <p><strong>Hoa Hồng: </strong> {{ $meta->commition->value }}</p>
        @endif

        <p><strong>Số điện thoại: </strong> {{ $meta->phone->value }}</p>
        <p><strong>Giá khoảng: </strong> {{ format_web_price($meta->price->value) ?? 'N/a' }}</p>
    </div>
</div>