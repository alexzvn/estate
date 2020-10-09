@extends('customer.layouts.app')

@section('content')
<div class="container">
    <div class="row">

        @include('customer.me.components.sidebar')

        <div class="col-lg-9 col-md-8">
            @yield('main-content')
        </div>
    </div>
<div>

{{-- @include('customer.components.post-create') --}}
@endsection

@push('style')
<style>
.wrap-info {
    background: #fff;
    margin-bottom: 20px;
    padding: 20px;
}
.wrap-avatar {
    height: 130px;
    width: 130px;
    border-radius: 50%;
    margin: auto;
    overflow: hidden;
    position: relative;
}
.wrap-avatar img {
    object-fit: cover;
}

.user-status-noti {
    background: #eee;
    padding: 2px 4px 4px 4px;
    font-size: 13px;
    font-weight: 500;
}
.wrap-avatar-info a {
    font-size: 13px;
    color: #555;
}
.frm-edit-user .form-group label {
    font-weight: 500;
    font-size: 15px;
}
.frm-edit-user .form-group input,
.frm-edit-user .form-group select {
    font-size: 14px;
}
.overlay-change-avatar {
    width: 100%;
    height: 60px;
    position: absolute;
    border: 0px;
    bottom: 0px;
    cursor: pointer;
    transition: background ease-out 0.15s;
    text-align: center;
    opacity: 0;
}
.wrap-avatar:hover .overlay-change-avatar {
    background: #00000054;
    opacity: 1;
    transition: all ease-in 0.15s;
}
.overlay-change-avatar i {
    margin-top: 13px;
    font-size: 16px;
    color: #ffffff;
}
.overlay-change-avatar span {
    font-size: 13px;
    font-weight: 500;
    color: #fff;
}
.pd-l-5 {
    padding-left: 5px;
}
.pd-r-5 {
    padding-right: 5px;
}
@media screen and (max-width: 767px) {
    .pd-l-5 {
        padding-left: 15px;
    }
    .pd-r-5 {
        padding-right: 15px;
    }
}
.lb-change-password {
    cursor: pointer;
}
.group-change-pass {
    padding: 20px;
    border: 1px solid #eee;
    border-radius: 5px;
    display: none;
}
.group-change-pass input {
    margin-bottom: 20px;
}
.group-change-pass input:last-child {
    margin-bottom: 0px;
}
</style>
@endpush