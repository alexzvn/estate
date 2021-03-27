@extends('customer.layouts.app')

@section('content')
<div class="container">

    <div class="row bg-white p-2 shadow rounded">
        
        @include('customer.components.tabs')

        <div class="col-md-12 mt-2">
            <div class="row">
                <div class="col-md-12 px-0" id="myTabContent">

                    <div class="text-center">
                        <h3 class="my-3 tw-text-2xl tw-text-green-500 tw-uppercase">Bảng giá nguồn chính chủ</h3>
                        <img class="tw-inline tw-transition-shadow tw-duration-400 hover:tw-shadow-2xl" src="{{ asset('assets/img/empty-state.jpg') }}?ver=1" alt="" style="height: 100%; max-width: 100%;">
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
