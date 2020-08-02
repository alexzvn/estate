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

        {{-- <div class="col-md-2 pl-md-0 order-first">
            <div class="form-group input-group-sm">
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

        <div class="col-md-2 pl-md-0 order-first">
            <div class="form-group input-group-sm">
                <select class="form-control" name="district" id="district">
                  <option value="">Chọn Quận/Huyện</option>
                  @foreach ($districts ?? $provinces->first()->districts ?? [] as $item)
                      <option value="{{ $item->id }}" {{ $item->id === request('district') ? 'selected' : ''}}>{{ $item->name }}</option>
                  @endforeach
                </select>
              </div>
        </div> --}}

        <div class="col-md-3 pl-md-0 order-md-first order-last">
            <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
            {{-- <a id="advanced-search" class="btn btn-outline-secondary" href="javascript:void(0)" role="button">Nâng cao</i> --}}
            </a>
        </div>

    </div>
</form>