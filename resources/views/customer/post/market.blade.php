@extends('customer.layouts.app')

@section('content')
<div class="container">

    <div class="row bg-white p-2 shadow rounded">
        
        @include('customer.components.tabs')

        @if (request()->user()->subscriptions->isEmpty())
        <div class="col-md-12 mt-2">
            <div class="row">
                <div class="col-md-12 px-0" id="myTabContent">
                    <div class="text-center">
                        <img src="{{ asset('assets/img/empty-state.jpg') }}?ver=1" alt="" style="height: 100%; max-width: 100%;">
                        <h3 style="color: cadetblue;">Có vẻ bạn chưa đăng ký gói tin nào. <br> Hãy liên hệ hotline để đăng ký và bắt đầu xem tin nhé!</h3>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

<div class="container mt-5">
    <div class="row">

        @foreach (range(1,20) as $item)
        <div class="col-xl-3 col-lg-4 col-sm-6 col-12 px-2">
            <div class="item-post">
                <div class="product-thumb">
                    <a class="show-list-img"
                        href="https://kientrucn8.com/wp-content/uploads/2018/07/khu-can-ho-vinhomes-times-city-4-640x400.jpg"
                        data-lightbox="{{ $loop->index }}" data-title="amee 0">
                        <img src="https://kientrucn8.com/wp-content/uploads/2018/07/khu-can-ho-vinhomes-times-city-4-640x400.jpg">
                    </a>

                </div>
                <div class="home-product-bound">
                    <ul class="wrap-list-detai-img">
                        <li>
                            <a href="https://i1-ione.vnecdn.net/2019/02/16/Amme2-1550288093.jpg?w=1200&h=0&q=100&dpr=2&fit=crop&s=dG3-_olDdskJQx6JroWdRg"
                                data-lightbox="{{ $loop->index }}" data-title="amee 1">123</a>
                        <li>
                            <a href="https://i1-ione.vnecdn.net/2019/02/16/Amme10-1550288079.jpg?w=1200&h=0&q=100&dpr=1&fit=crop&s=yFllgV7WPij5ChK8NZr6mw"
                                data-lightbox="{{ $loop->index }}" data-title="Amee 2">123</a>

                        </li>
                        <li> <a href="https://i1-ione.vnecdn.net/2019/02/16/Amme8-1550288094.jpg?w=1200&h=0&q=100&dpr=2&fit=crop&s=4rwm1RDu-30va3u2zIBP2Q"
                                data-lightbox="{{ $loop->index }}" data-title="Amee 3">123</a></li>
                    </ul>
                    <div class="p-title">
                        <a class="show-list-img" href="https://kientrucn8.com/wp-content/uploads/2018/07/khu-can-ho-vinhomes-times-city-4-640x400.jpg"
                        data-lightbox="{{ $loop->index }}" data-title="amee 0">Bán gấp nhà đất mặt
                            tiền đường nhựa 763, thuận tiện buôn bán
                        </a>
                    </div>
                    <div class="product-info mt-3">
                        <img src="/assets/img/selection.png" width="16"> <strong>889.2 m²</strong>
                    </div>
                    <div class="product-info">
                        <img src="/assets/img/location.png" width="18">
                        <a href="/ban-nha-mat-pho-dinh-quan-dna" title="Bán nhà mặt phố tại Định Quán">Định Quán</a>,
                        <a href="/ban-nha-mat-pho-dong-nai" title="Bán nhà mặt phố tại Đồng Nai">Đồng Nai</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

@include('customer.components.post-create')
@endsection

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    (function (window) {
        $(document).ready(function () {
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'alwaysShowNavOnTouchDevices': true,
                'disableScrolling': true,
                'fitImagesInViewport': true,
                'fadeDuration': 200,
                'albumLabel': 'Ảnh %1 trong số %2 ảnh',
            });
        });
    }(window))
</script>
@endpush

@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<style>
    .home-product-bound .product-price {
        font-weight: 700;
        font-size: 18px;
        line-height: 25px;
        color: #055699;
    }

    .home-product-bound {
        padding: 13px 8px;
        border: 1px solid #ECECEC;
        border-top: 0;
        box-sizing: border-box;
        border-radius: 0 0 5px 5px;
    }

    .product-thumb {
        width: 100%;
        height: 160px;
        border-radius: 5px 5px 0 0;
    }

    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 5px 5px 0 0;
    }

    .product-info {
        font-size: 14px;
        line-height: 26px;
        color: #777;
    }

    .product-info img {
        float: left;
        margin-top: 5px;
        margin-right: 13px;
    }

    .item-post {
        border-radius: 5px;
        background: #fff;
        margin-bottom: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: box-shadow ease-in .25s;
    }

    .item-post:hover {
        transition: box-shadow ease-out .25s;
        box-shadow: 0 11px 14px rgba(0, 0, 0, 0.25)
    }

    .home-product-bound .product-info a {
        font-weight: normal;
        font-size: 14px;
        color: #777;
        line-height: 22px;
        margin-bottom: 7px;
    }

    .home-product-bound .p-title {
        height: 65px;
    }

    .home-product-bound .p-title a {
        overflow: hidden;
        font-weight: 700;
        font-size: 16px;
        line-height: 22px;
        color: #333;
        margin-bottom: 7px;
        text-decoration: none;
    }

    .wrap-list-detai-img {
        display: none;
    }


    @media only screen and (max-width: 1200px) {
        .product-thumb {
            height: 170px;
        }
    }

    @media only screen and (max-width: 768px) {
        .product-thumb {
            height: 140px;
        }
    }

    @media only screen and (max-width: 576px) {
        .product-thumb {
            height: 220px;
        }

        .home-product-bound .p-title {
            height: 50px;
        }
    }
</style>
@endpush