@php
$contains = collect();

foreach ($keywords as $keyword) {
    if ($keyword->test("$post->title $post->content")) {
        $contains->push($keyword->key);
    }
}
@endphp

@if ($contains->isNotEmpty() || $post->blacklists)
<br>
<span class="mb-0" style="font-size: 12px;">
    @if ($contains->isNotEmpty())
    <strong>Từ khóa: </strong> <i class="text-warning">{{ $contains->join(', ') }}</i>
    @endif

    @if ($contains->isNotEmpty() && $post->blacklists) | @endif

    @if ($post->blacklists)
    <strong>SĐT đen: </strong> <i class="text-primary">{{ $post->blacklists->phone }}</i>
    @endif
</span>
@endif
