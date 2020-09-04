<?php use App\Enums\PostType; ?>

@extends('dashboard.app')
@push('style')
<link rel="stylesheet" href="{{ asset('dashboard/plugins/file-upload/file-upload-with-preview.min.css') }}">
<link rel="stylesheet" href="{{ asset('dashboard/assets/css/forms/theme-checkbox-radio.css') }}">
<link rel="stylesheet" href="{{ asset('dashboard/plugins/lightbox/photoswipe.css') }}">
<link rel="stylesheet" href="{{ asset('dashboard/plugins/lightbox/default-skin/default-skin.css') }}">
<link rel="stylesheet" href="{{ asset('dashboard/plugins/lightbox/custom-photswipe.css') }}">

<style>
    .card-img-header {
        max-height: 13rem;
        border-top-left-radius: calc(.25rem - 1px);
        border-top-right-radius: calc(.25rem - 1px);
        object-fit: cover;
    }

    .t-icon {
        padding: 3px;
    }

    .card-text {
        line-height: 1.8;
    }
</style>
@endpush

@section('content')
<div class="col-12 mb-4">
    <h4>
        Danh sách bài viết
        <a href="{{ route('manager.post.create') }}" class="btn btn-success rounded-circle"><i data-feather="plus"></i></a>
    </h4>
</div>

@foreach ($posts as $post)
@php
    $post->loadMeta();
@endphp
<div class="col-md-4 col-sm-6 mb-3">
    <div class="card component-card_2">
        <img src="{{ '/storage/' . $post->files[0]->path ?? '' }}" class="card-img-header cursor-pointer" data-images='@json($post->files)'>
        <div class="card-body">
            <h5 class="card-title">{{ $post->title }}</h5>
            <p class="card-text">
                <i class="t-icon" data-feather="phone"></i> {{ $post->meta->phone->value ?? '' }} <br>
                <i class="t-icon" data-feather="bookmark"></i> {{ $post->categories[0]->name ?? '' }} <br>
                <i class="t-icon" data-feather="map"></i> {{ $post->meta->province->province->name ?? '' }} <br>
                <i class="t-icon" data-feather="map-pin"></i> {{ $post->meta->district->district->name ?? '' }}
            </p>
        </div>
        <div class="card-footer">
            <a href="#" class="btn btn-primary">Chỉnh sửa</a>
        </div>
    </div>
</div>
@endforeach

<div class="col-12 justify-content-center">
    {{ $posts->withQueryString()->render() }}
</div>

@include('dashboard.layouts.photoswipe')
@endsection

@push('script')
<script src="{{ asset('dashboard/plugins/lightbox/photoswipe.min.js') }}"></script>
<script src="{{ asset('dashboard/plugins/lightbox/photoswipe-ui-default.min.js') }}"></script>

<script>
(function (window) {
$(document).ready(function () {
    $('.card-img-header').on('click', function () {
        const images = $(this).data('images').map(image => {
            return {src: `/storage/${image.path}`, w: 0, h: 0};
        });

        let pswpElement = document.querySelectorAll('.pswp')[0];

        let gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, images, {});

        gallery.listen('gettingData', function(index, item) {
            if (item.w < 1 || item.h < 1) { // unknown size
                let img = new Image(); 
                img.onload = function() { // will get size after load
                    item.w = this.width; // set image width
                    item.h = this.height; // set image height
                    gallery.invalidateCurrItems(); // reinit Items
                    gallery.updateSize(true); // reinit Items
                }
                img.src = item.src; // let's download image
            }
        });

        gallery.init();
    });
});
}(window))
</script>
@endpush