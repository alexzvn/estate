@push('style')
<link rel="stylesheet" href="{{ asset('dashboard/plugins/flatpickr/flatpickr.css') }}">
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

        <div class="col-md-2 pl-md-0 order-first">
            <div class="form-group input-group-sm">
              <select class="form-control" name="plan" id="plan">
                <option value="">Gói tin</option>
                @foreach ($plans as $plan)
                <option value="{{ $plan->id }}" {{ request('plan') === $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                @endforeach
              </select>
            </div>
        </div>

        <div class="col-md-2 pl-md-0 order-first">
            <div class="form-group input-group-sm">
              <select class="form-control" name="creator" id="creator">
                <option value="">Người tạo</option>
                @foreach ($staff as $user)
                <option value="{{ $user->id }}" {{ request('creator') === $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
              </select>
            </div>
        </div>

        <div class="col-md-2 pl-md-0 order-first">
            <div class="form-group input-group-sm">
              <div class="form-check">
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="price_greater" id="price_greater" value="10" {{ request('price_greater') ? 'checked' : '' }}>
                  Bỏ qua đơn 0đ
                </label>
              </div>
            </div>
        </div>

        <div class="col-md-2 pl-md-0 order-md-first order-last">
            <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
            <a id="advanced-search" class="btn btn-outline-secondary" href="javascript:void(0)" role="button"> <i data-feather="filter"></i>
            </a>
        </div>

        <div class="col-md-12">
            <div id="advanced-search-form" class="row" style="display: none;">

                <div class="col-md-4 pl-md-0">
                    <div class="form-row">
                        <div class="offset-md-3 col-md-9">
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group input-group-sm">
                                        <input type="text" class="form-control" name="activated_from" id="from" data-date-orientation="bottom auto"
                                          data-provide="datepicker" placeholder="Chọn từ ngày..." value="{{ request('activated_from') }}">
                                          <small class="ml-2 form-text text-muted">Kích hoạt từ ngày</small>
                                      </div>
                                </div>
                                <div class="col">
                                    <div class="form-group input-group-sm">
                                      <input type="text" class="form-control" name="activated_to" id="to" data-date-orientation="bottom auto"
                                      data-provide="datepicker" placeholder="Đến ngày" value="{{ request('activated_to') }}">
                                      <small class="ml-2 form-text text-muted">Kích hoạt đến ngày</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</form>

@push('script')
<script src="{{ asset('dashboard/plugins/flatpickr/flatpickr.js') }}"></script>
<script>
    let advancedSearch;

    if ((openSearch = getCookie('open_search')) && openSearch !== '') {
        advancedSearch = openSearch === 'true';
        if (advancedSearch) {
            $('#advanced-search-form').show();
        }

    } else {
        advancedSearch = false;
        setCookie('open_search', 'false');
    }

    $(document).ready(function () {

        let f2 = flatpickr(document.getElementById('to'), {
            enableTime: false,
            dateFormat: "d/m/Y",
        });

        let f3 = flatpickr(document.getElementById('from'), {
            enableTime: false,
            dateFormat: "d/m/Y",
        });

        $('#search-form').on('change', function () {
            $(this).submit();
        });
    });

    $(document).ready(function () {
        $('#advanced-search').click(function () {
            if (advancedSearch = !advancedSearch) {
                $('#advanced-search-form').fadeIn();
            } else {
                $('#advanced-search-form').fadeOut();
            }

            setCookie('open_search', ''+ advancedSearch)
        });

        $('#search-form').change(function name() {
            $('#search-form').submit();
        });

        $('#province').on('change', function () {
            let id = $('#province').val();
            let district = $('#district');

            let province = address.filter((e) => {return e.id === id})[0];

            district.html('');
            district.append('<option value="" selected>Chọn</option');

            if (province === undefined) return;

            province.districts.map((e) => {
                district.append(`<option value="${e.id}">${e.name}</option`);
            });
        });
    });

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
            c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function setCookie(cname, cvalue) {
        var d = new Date();
        d.setTime(d.getTime() + (2 * 60 * 1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
</script>
@endpush