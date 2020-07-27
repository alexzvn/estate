<div class="col-md-12 px-0">
    <div class="border rounded-top">
        <ul class="nav nav-tabs nav-custom-tabs mx-3">
            <li class="nav-item">
                <a class="nav-link @active('home')" href="{{ route('home') }}">Tất cả</a>
            </li>
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