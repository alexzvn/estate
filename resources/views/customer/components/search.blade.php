<form action="" method="GET">

    <div class="row">
        <div class="col-md-5 pl-0">
            <div class="form-row">
                <label for="query" class="col-md-3 col-form-label text-md-right"><strong>Tìm kiếm</strong></label>

                <div class="col-md-9">
                    <input id="query" type="text" class="form-control" name="query" value="{{ request('query') }}" placeholder="Nhập từ khóa tìm kiếm...">
                </div>
            </div>
        </div>

        <div class="col-md-2 pl-0">
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

        <div class="col-md-2 pl-0">
            <div class="form-group">
                <select class="form-control" name="district" id="district">
                  <option value="">Chọn Quận/Huyện</option>
                  @foreach ($districts ?? $provinces->first()->districts ?? [] as $item)
                      <option value="{{ $item->id }}" {{ $item->id === request('district') ? 'selected' : ''}}>{{ $item->name }}</option>
                  @endforeach
                </select>
              </div>
        </div>

        <div class="col-md-3 pl-0">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            <a class="btn btn-link" href="#" role="button">Tìm kiếm nâng cao <i class="fa fa-caret-down" aria-hidden="true"></i>
            </a>
        </div>
    </div>

</form>
