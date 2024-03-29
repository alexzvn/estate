@extends('customer.layouts.app')

@section('content')
<div class="container">

    <div class="row bg-white p-2 shadow rounded">
        
        @include('customer.components.tabs')

        @if (request()->user()->subscriptions->isEmpty() || (isset($canAccess) && $canAccess === false))
        <div class="col-md-12 mt-2">
            <div class="row">
                <div class="col-md-12 px-0">
                    <div class="text-center">
                        @if (request()->user()->subscriptions)
                        <h3 class="my-3 tw-text-2xl tw-text-red-500 tw-uppercase">Tài khoản của bạn đã hết hạn. <br> Hãy liên hệ hotline 096 55.33.958 để gia hạn gói mới!</h3>
                        @else
                        <h3 class="my-3 tw-text-2xl tw-text-green-500 tw-uppercase">Có vẻ bạn chưa đăng ký gói tin nào. <br> Hãy liên hệ hotline 096 55.33.958 để đăng ký và bắt đầu xem tin nhé!</h3>
                        @endif
                        <img class="tw-inline" src="{{ asset('assets/img/empty-state.jpg') }}?ver=1" alt="" style="height: 100%; max-width: 100%;">
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="col-md-12 my-3">
            <div class="row">
    
                @foreach ($posts as $post)
                @php
                    $firstImages = $post->files->shift();
                @endphp
        
                <div class="col-xl-3 col-lg-4 col-sm-6 col-xs-12 px-2 mt-3">
                    <div class="item-post h-100">
                        <div class="product-thumb">
                            @isset ($firstImages)
                            <a class="show-list-img" id="{{ $post->id }}" href="{{ asset($firstImages->path) }}"
                                data-lightbox="{{ $post->id }}" data-title="">
                                <img src="{{ asset($firstImages->path) }}">
                            </a>
                            @endisset
                        </div>
                        <div class="home-product-bound">
                            <ul class="wrap-list-detai-img">
                                @foreach ($post->files as $image)
                                <li>
                                    <a href="{{ asset($image->path) }}"
                                        data-lightbox="{{ $post->id }}" data-title="">123</a>
                                <li>
                                @endforeach
                            </ul>
                            <div class="p-title">
                                <a class="trigger-show-list-img" data-id="{{ $post->id }}" href="javascript:void(0)"> {{ $post->title }}
                                </a>
                            </div>
                            @if ($category = $post->categories->first())
                            <div class="product-info">
                                {{ $category->name }}
                            </div>
                            @endif
                            <div class="product-info mt-2">
                                <i class="fa fa-clock-o mr-1"></i>
                               {{ $post->publish_at ? $post->publish_at->format('d/m/Y') : 'Không rõ' }}
                            </div>
                            <div class="product-info">
                                <img src="{{ asset('/assets/img/selection.png') }}" width="16"> <strong>{{ $post->phone ?? '' }}</strong>
                            </div>
                            <div class="product-info">
                                <img src="{{ asset('/assets/img/location.png') }}" width="18">
                                <a href="javascript:void(0)">{{ $post->district->name }}</a>,
                                <a href="javascript:void(0)">{{ $post->province->name }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
        
            </div>

            <div class="d-flex justify-content-center">
                {{ $posts->withQueryString()->onEachSide(1)->links('customer.layouts.paginate') }}
            </div>
        </div>

    </div>
</div>

@include('customer.components.post-create')
@endsection

@include('customer.components.feature-popup', ['type' => 'market'])

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    (function (window) {
        function eventFire(el, etype){
            if (el.fireEvent) {
                el.fireEvent('on' + etype);
            } else {
                var evObj = document.createEvent('Events');
                evObj.initEvent(etype, true, false);
                el.dispatchEvent(evObj);
            }
        }

        $(document).ready(function () {
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'alwaysShowNavOnTouchDevices': true,
                'disableScrolling': true,
                'fitImagesInViewport': true,
                'fadeDuration': 200,
                'positionFromTop': 10,
                'albumLabel': 'Ảnh %1 trong số %2 ảnh',
            });

            $('.trigger-show-list-img').on('click', function () {
                let id = $(this).data('id');

                eventFire(document.getElementById('id'), 'click');
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
