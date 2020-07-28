<div class="col-md-12 px-0">
    <div class="border rounded-top">
        <ul class="nav nav-tabs nav-custom-tabs mx-3">
            <li class="nav-item">
                <a class="nav-link @active('post.online')" href="{{ route('post.online') }}">Tin Online</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @active('post.fee')" href="{{ route('post.fee') }}">Tin Xin Phí</a>
            </li>
            <li class="nav-item d-none">
                <a class="nav-link">Tin thị trường</a>
            </li>
        </ul>
        <div class="border-top p-3" style="background-color: #f7f7f7;">
            @include('customer.components.search')
        </div>
    </div>
</div>

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