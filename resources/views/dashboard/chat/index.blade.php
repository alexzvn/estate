@extends('dashboard.app')

@push('style')
<link href="{{ asset('dashboard/assets/css/apps/mailing-chat.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('script')
<script> window._conversations = @json($conversations) </script>
<script> window._user          = @json(user()) </script>
<script src="{{ asset('assets/js/chat.js') }}"></script>
@endpush

@section('content')
<div id="chat" class="col-xl-12 col-lg-12 col-md-12">
    <chat></chat>
</div>
@endsection
