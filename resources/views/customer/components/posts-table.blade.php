<table class="table table-striped table-hover">
    <thead class="thead-light">
      <tr>
        <th scope="col" width="6%">TT</th>
        <th scope="col" width="75%">Tiêu đề</th>
        <th scope="col">Giá</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($posts as $item)
      @php
        $meta = $item->loadMeta()->meta;
      @endphp
      <tr class="cursor-pointer" data-post-id="{{ $item->id }}">
        <th class="text-muted" scope="row">{{ $loop->iteration }}</th>
        <td>
            <p class="mb-0"><i class="fa fa-file-text-o"></i> <strong>{{ Str::ucfirst(Str::of($item->title)->limit(73)) }}</strong> <br>

            <span class="mb-0" style="font-size: 12px;">
                <strong>Danh mục: </strong> <i style="color: blue">{{ $item->categories[0]->name ?? '' }}</i> <span class="text-muted">|</span>
                <strong>Quận/huyện: </strong> <i style="color: blue">{{ $meta->district->district->name ?? 'N/a' }}</i> <span class="text-muted">|</span>
                <strong>Ngày đăng: </strong> <i style="color: blue">{{ $item->publish_at ? $item->publish_at->format('d/m/Y') : 'N/a' }}</i>
            </span>
            </p>
        </td>
        <td><strong  style="color: red;">{{ format_web_price($meta->price->value) ?? 'N/a' }}</strong></td>
      </tr>
      @endforeach
    </tbody>
</table>