@push('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/datepicker/css/bootstrap-datepicker.standalone.min.css') }}">
@endpush

<form id="search-form" action="" method="GET">
    <div class="row">
        <div class="col-md-5 pl-md-0">
            <div class="form-row">
                <label for="query" class="col-md-3 col-form-label text-md-right d-none d-md-block"><strong>Tìm kiếm: </strong></label>

                <div class="col-md-9">
                    <div class="form-group">
                    <input id="query" type="text" class="form-control" name="query" value="{{ request('query') }}" placeholder="Nhập từ khóa tìm kiếm...">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2 pl-md-0">
            <div class="form-group">
              <select class="form-control" name="categories" id="categories">
                <option value="">Chọn chuyên mục</option>
                @foreach ($categories as $item)
                <option style="color: black; font-weight: bold;" disabled>{{ $item->name }}</option>
                    @foreach ($item->children ?? [] as $item)
                    <option value="{{ $item->id }}" {{ $item->id === request('categories') ? 'selected' : ''}}>{{ $item->name }}</option>
                    @endforeach
                @endforeach
              </select>
            </div>
        </div>

        <div class="col-md-2 pl-md-0">
            <div class="form-group">
                <select class="form-control" name="district" id="district">
                  <option value="">Chọn Quận/Huyện</option>
                  @foreach ($districts ?? $provinces->first()->districts ?? [] as $item)
                      <option value="{{ $item->id }}" {{ $item->id === request('district') ? 'selected' : ''}}>{{ $item->name }}</option>
                  @endforeach
                </select>
              </div>
        </div>

        <div class="col-md-3 pl-md-0">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            <a id="advanced-search" class="btn btn-link" href="javascript:void(0)" role="button">Tìm kiếm nâng cao <i class="fa fa-caret-down" aria-hidden="true"></i>
            </a>
        </div>
    </div>

    <div id="advanced-search-form" class="row" style="display: none;">
        
        <div class="col-md-5 pl-md-0">
            <div class="form-row">
                <div class="offset-md-3 col-md-9">
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <input type="text" class="form-control" name="from" id="from" 
                                  data-provide="datepicker" placeholder="Chọn từ ngày..." value="{{ request('from') }}">
                                  <small class="form-text text-muted">Chọn tin từ ngày</small>
                              </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                              <input type="text" class="form-control" name="to" id="to"
                              data-provide="datepicker" placeholder="Đến ngày" value="{{ request('to') }}">
                              <small class="form-text text-muted">Chọn tin đến ngày</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2 pl-md-0">
            <div class="form-group">
                <select class="form-control" name="province" id="province">
                  <option value="">Chọn Tỉnh/TP</option>
                  @foreach ($provinces ?? [] as $item)
                      <option value="{{ $item->id }}" {{ $item->id === request('province') ? 'selected' : ''}}>{{ $item->name }}</option>
                  @endforeach
                </select>
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

            let province = address.filter((e) => {return e._id === id})[0];

            district.html('');
            district.append('<option value="" selected>Chọn</option');

            if (province === undefined) return;

            province.districts.map((e) => {
                district.append(`<option value="${e._id}">${e.name}</option`);
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

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

}(window));
</script>
@endpush