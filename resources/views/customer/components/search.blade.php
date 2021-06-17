@push('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/datepicker/css/bootstrap-datepicker.standalone.min.css') }}">
@endpush

@php
$filterPrices = [
    '-1000000' => '< 1 triệu',
    '1000000-3000000' => '1 - 3 triệu',
    '3000000-5000000' => '3 - 5 triệu',
    '5000000-10000000' => '5 - 10 triệu',
    '10000000-20000000' => '10 - 20 triệu',
    '20000000-30000000' => '20 - 30 triệu',
    '30000000-40000000' => '30 - 40 triệu',
    '40000000-70000000' => '40 - 70 triệu',
    '70000000-100000000' => '70 - 100 triệu',
    '100000000-500000000' => '100 - 500 triệu',
    '500000000-800000000' => '500 - 800 triệu',
    '800000000-1000000000' => '800 triệu - 1 tỷ',
    '1000000000-2000000000' => '1 - 2 tỷ',
    '2000000000-3000000000' => '2 - 3 tỷ',
    '3000000000-5000000000' => '3 - 5 tỷ',
    '5000000000-7000000000' => '5 - 7 tỷ',
    '7000000000-10000000000' => '7 - 10 tỷ',
    '10000000000-20000000000' => '10 - 20 tỷ',
    '20000000000-30000000000' => '20 - 30 tỷ',
    '30000000000-' => '> 30 tỷ',
];

$districts = $provinces->where('id', request('province'))->first() ?? $provinces->first();
$districts = $districts->districts ?? null;
@endphp

<form id="search-form" action="" method="GET" autocomplete="off">
    <div class="row">
        <div class="col-md-5 pl-md-0 order-first">
            <div class="form-row">
                <label for="query" class="col-md-3 col-form-label text-md-right d-none d-md-block"><strong>Tìm kiếm: </strong></label>

                <div class="col-md-9">
                    <div class="form-group">
                    <input id="query" type="text" class="form-control" name="query" value="{{ request('query') }}" placeholder="Nhập từ khóa tìm kiếm...">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2 pl-md-0 order-first">
            <div class="form-group">
              <select class="form-control" name="categories" id="categories">
                <option value="">Chọn chuyên mục</option>
                @foreach ($categories as $item)
                <option style="color: black; font-weight: bold;" disabled>{{ $item->name }}</option>
                    @foreach ($item->children ?? [] as $item)
                    <option value="{{ $item->id }}" {{ $item->id == request('categories') ? 'selected' : ''}}>{{ $item->name }}</option>
                    @endforeach
                @endforeach
              </select>
            </div>
        </div>

        <div class="col-md-2 pl-md-0 order-first">
            <div class="form-group">
                <select class="form-control" name="district" id="district">
                  <option value="">Chọn Quận/Huyện</option>
                  @foreach ($districts ?? $provinces->first()->districts ?? [] as $item)
                      <option value="{{ $item->id }}" {{ $item->id == request('district') ? 'selected' : ''}}>{{ $item->name }}</option>
                  @endforeach
                </select>
              </div>
        </div>

        <div class="col-md-3 pl-md-0 order-md-first order-last">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            <a id="advanced-search" class="btn btn-link" href="javascript:void(0)" role="button">Tìm kiếm nâng cao <i class="fa fa-caret-down" aria-hidden="true"></i>
            </a>
        </div>

        <div class="col-md-12">
            <div id="advanced-search-form" class="row" style="display: none;">
                <div class="col-md-5 pl-md-0">
                    <div class="form-row">
                        <div class="offset-md-3 col-md-9">
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="from" id="from" autocomplete="off"
                                          data-provide="datepicker" placeholder="Chọn từ ngày..." value="{{ request('from') }}" data-date-format="dd-mm-yyyy">
                                          <small class="ml-2 form-text text-muted">Chọn tin từ ngày</small>
                                      </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                      <input type="text" class="form-control" name="to" id="to" autocomplete="off"
                                      data-provide="datepicker" placeholder="Đến ngày" value="{{ request('to') }}" data-date-format="dd-mm-yyyy">
                                      <small class="ml-2 form-text text-muted">Chọn tin đến ngày</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="col-md-2 pl-md-0">
                    <div class="form-group">
                        <select class="form-control" name="price" id="price">
                          <option value="">Chọn khoảng giá</option>
                          @foreach ($filterPrices as $value => $name)
                              <option value="{{ $value }}" {{ $value == request('price') ? 'selected' : ''}}>{{ $name }}</option>
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 pl-md-0">
                    <div class="form-group">
                        <select class="form-control" name="province" id="province">
                          <option value="">Chọn Tỉnh/TP</option>
                          @foreach ($provinces ?? [] as $item)
                              <option value="{{ $item->id }}" {{ $item->id == request('province') ? 'selected' : ''}}>{{ $item->name }}</option>
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 pl-md-0">
                    <div class="form-group">
                        <select class="form-control" name="order" id="order">
                          <option value="">Cách sắp xếp</option>
                          <option value="newest" {{ request('order') == 'newest' ? 'selected' :'' }}>Mới nhất</option>
                          <option value="accurate" {{ request('order') == 'accurate' ? 'selected' :'' }}>Chính xác nhất</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

    </div>


</form>

@push('script')
<script src="{{ asset('assets/vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datepicker/locales/bootstrap-datepicker.vi.min.js') }}"></script>
<script>
(function (window) {

    let advancedSearch;
    let address = JSON.parse('@json($provinces)');

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

            let province = address.filter((e) => {return e.id == id})[0];

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

}(window));
</script>
@endpush
