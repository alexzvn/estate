@push('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/datepicker/css/bootstrap-datepicker.standalone.min.css') }}">
@endpush

<form id="search-form" action="" method="GET" autocomplete="off">
    <input type="hidden" name="roles" value="{{ request('roles') }}">

    <div class="row">
        <div class="col-md-4 pl-md-0 order-first">
            <div class="form-row">
                <label for="query" class="col-md-3 col-form-label text-md-right d-none d-md-block"><strong>Tìm kiếm: </strong></label>

                <div class="col-md-9">
                    <div class="form-group input-group-sm">
                    <input id="query" type="text" class="form-control" name="query" value="{{ request('query') }}" placeholder="Tìm kiếm thông tin">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2 order-first pl-md-0">
            <div class="form-group input-group-sm">
                <input type="text" class="form-control" name="from" id="from" data-date-orientation="bottom auto" data-date-format="dd/mm/yyyy"
                  data-provide="datepicker" placeholder="Chọn từ ngày..." value="{{ request('from') }}">
                  <small class="ml-2 form-text text-muted">Đăng ký từ ngày</small>
              </div>
        </div>

        <div class="col-md-2 order-first pl-md-0">
            <div class="form-group input-group-sm">
              <input type="text" class="form-control" name="to" id="to" data-date-orientation="bottom auto" data-date-format="dd/mm/yyyy"
              data-provide="datepicker" placeholder="Đến ngày" value="{{ request('to') }}">
              <small class="ml-2 form-text text-muted">Đăng ký đến ngày</small>
            </div>
        </div>

        <div class="col-md-2 pl-md-0 order-first">
            <div class="form-group input-group-sm">
              <select class="form-control" name="status" id="status">
                <option>Trạng thái</option>
                @foreach (\App\Models\User::getStatusKeyName() as $key => $value)
                    <option value="{{ $key }}" {{ $key == request('status') ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
              </select>
              <small class="ml-2 form-text text-muted">Trạng thái người dùng</small>
            </div>
        </div>


        <div class="col-md-2 pl-md-0 order-md-first order-last">
            <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
            <a id="advanced-search" class="btn btn-outline-secondary" href="javascript:void(0)" role="button"> <i data-feather="filter"></i>
            </a>
        </div>

    </div>
</form>

@push('script')
<script src="{{ asset('assets/vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datepicker/locales/bootstrap-datepicker.vi.min.js') }}"></script>
<script>


    $(document).ready(function () {
        $('#search-form').on('change', function () {
            $(this).submit();
        });
    });

</script>
@endpush