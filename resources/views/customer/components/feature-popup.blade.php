@php
    $key = "popup.$type";
@endphp

@if ($setting->get($key) && !session($key, false))

    @php session([$key => true]); @endphp

    @push('style')
        <link rel="stylesheet" href="{{ asset('assets/css/ckeditor.css') }}">
    @endpush

    @push('before-body')
    <div id="popup-info" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header tw-p-3">
                    <p>&nbsp;</p>
                    <div class="tw-text-center">
                        <h5 class="modal-title tw-text-xl tw-text-red-500 tw-font-semibold tw-uppercase">Thông báo</h5>
                    </div>
                    <button class="tw-text-xl" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ck-content">
                    {!! $setting->get($key) !!}
                </div>
            </div>
        </div>
    </div>
    @endpush

    @push('script')
    <script>
        $(document).ready(() => {
            $('#popup-info').modal()
        })
    </script>
    @endpush
@endif



