@push('style')
<link rel="stylesheet" href="{{ asset('dashboard/plugins/flatpickr/flatpickr.css') }}">
@endpush

<form id="search-form" action="" method="GET">
    <div class="row">
        <div class="col-md-5 pl-md-0 order-first">
            <div class="form-row">
                <label for="query" class="col-md-3 col-form-label text-md-right d-none d-md-block"><strong>Tìm kiếm: </strong></label>

                <div class="col-md-9">
                    <div class="form-group input-group-sm">
                    <input id="query" type="text" class="form-control" name="query" value="{{ request('query') }}" placeholder="Nhập từ khóa tìm kiếm...">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2 pl-md-0 order-first">
            <div class="form-group input-group-sm">
              <input type="text"
                class="form-control" name="expires_date" id="expires_date" value="{{ request('expires_date') }}" placeholder="Ngày hết hạn">
            </div>
        </div>

        <div class="col-md-2 pl-md-0 order-first">
            <div class="form-group input-group-sm">
              <div class="form-check">
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="me" id="me" value="true" {{ request('me') ? 'checked' : '' }}>
                  Đang quản lý
                </label>
              </div>
            </div>
        </div>

        <div class="col-md-3 pl-md-0 order-md-first order-last">
            <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
            {{-- <a id="advanced-search" class="btn btn-outline-secondary" href="javascript:void(0)" role="button">Nâng cao</i> --}}
            </a>
        </div>

    </div>
</form>

@push('script')
<script src="{{ asset('dashboard/plugins/flatpickr/flatpickr.js') }}"></script>
<script>
    $(document).ready(function () {
        let f1 = flatpickr(document.getElementById('expires_date'), {
            enableTime: false,
            dateFormat: "d/m/Y",
        });

        $('#search-form').on('change', function () {
            $(this).submit();
        });
    });
</script>
@endpush