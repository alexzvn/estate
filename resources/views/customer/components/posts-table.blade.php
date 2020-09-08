@php
  $hasCommission = !empty($posts[0]) && isset($posts[0]->commission);
@endphp

<table class="table table-striped table-hover">
    <thead class="thead-light">
      <tr>
        <th scope="col">TT</th>
        <th scope="col">Tiêu đề</th>
        @if($hasCommission)
        <th scope="col" class="d-none d-lg-table-cell">Hoa hồng</th>
        @endif
        <th scope="col" class="d-none d-lg-table-cell">Giá</th>
        <th scope="col" class="d-none d-lg-table-cell">Số điện thoại</th>
        <th scope="col" class="d-none d-lg-table-cell">Thao tác</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($posts as $item)
      <tr>
        <th class="text-muted" scope="row">{{ $loop->iteration }}</th>
        <td class="cursor-pointer" data-post-id="{{ $item->id }}">
            <p class="mb-0"><i class="fa fa-file-text-o"></i> <strong>{{ Str::ucfirst(Str::of($item->title)->limit(73)) }}</strong> <br>

            <span class="mb-0" style="font-size: 12px;">
                <strong>Danh mục: </strong> <i style="color: blue">{{ $item->categories[0]->name ?? '' }}</i> <span class="text-muted">|</span>
                <strong>Quận/huyện: </strong> <i style="color: blue">{{ $meta->district->district->name ?? 'N/a' }}</i> <span class="text-muted">|</span>
                <strong>Ngày đăng: </strong> <i style="color: blue">{{ $item->publish_at ? $item->publish_at->format('d/m/Y') : 'N/a' }}</i>
            </span>
            </p>
            <p class="d-block d-lg-none" style="color: red">
              <strong> Giá khoảng {{ isset($meta->price->value) ? format_web_price($meta->price->value) : 'N/a' }} </strong>
              @isset($meta->commission->value)
              - <strong> Hoa Hồng {{ $meta->commission->value }}</strong>
              @endisset
            </p>
        </td>
        @if($hasCommission)
        <td class="d-none d-lg-table-cell"> <strong>{{ $meta->commission->value ?? '' }}</strong> </td>
        @endif
        <td class="d-none d-lg-table-cell"><strong>{{ isset($meta->price->value) ? format_web_price($meta->price->value) : 'N/a' }}</strong></td>
        <td class="d-none d-lg-table-cell">
          @isset($meta->phone->value)
          <span onclick="$(this).html($(this).data('phone'))" data-phone="{{ $meta->phone->value }}">
              <button class="btn btn-sm btn-success">Xem SĐT</button>
          </span>
          @else
          N/a
          @endisset
        </td>
        <td class="d-none d-lg-table-cell">
          <div class="d-flex">
            @php
                $saved = in_array($item->id, auth()->user()->post_save_ids ?? []);
                $deleted = in_array($item->id, auth()->user()->post_blacklist_ids ?? []);
            @endphp
            <button class="save-element btn btn-sm btn-primary mr-1" data-active="{{ $saved ? 'true' : 'false' }}" data-id="{{ $item->id }}" type="button">{{ $saved ? 'Bỏ lưu' : 'Lưu' }}</button>
            <button class="delete-element btn btn-sm btn-danger" data-active="{{ $deleted ? 'true' : 'false' }}" data-id="{{ $item->id }}" type="button">{{ $deleted ? 'Bỏ xóa' : 'Xóa' }}</button>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
</table>

@push('script')
<script>
(function (window) {

  $(document).ready(function () {
    $('.save-element').on('click', function () {
      let id = $(this).data('id');

      fetchAction(`/post/${id}/action/save`);

      let active = !$(this).data('active');

      $(this).data('active', active);
      active ? $(this).html('Bỏ lưu') : $(this).html('Lưu');

    });

    $('.delete-element').on('click', function () {
      let id = $(this).data('id');

      if (confirm('Bạn có muốn xóa tin này không?')) {
        fetchAction(`/post/${id}/action/blacklist`);
        let active = !$(this).data('active');

        $(this).data('active', active);
        active ? $(this).html('Bỏ xóa') : $(this).html('Xóa');
      }
    });
  });

  function fetchAction(url) {
    let notify = function (text) {
      Snackbar.show({
        text: text,
        pos: 'bottom-right',
        duration: 2000,
        showAction: false,
      });
    }

      fetch(url)
      .then(res => {
          if (res.ok || res.status === 404) {
              return res.text();
          } else {
            notify('Có lỗi trong quá trình thực hiện, \n xin hãy thử làm mới lại trang');
          }
      }).then(text => {
        notify(text);
      });
  }

}(window))
</script>
@endpush