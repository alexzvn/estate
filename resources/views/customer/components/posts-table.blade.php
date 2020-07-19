<table class="table table-hover">
    <thead>
      <tr>
        <th scope="col" width="6%">TT</th>
        <th scope="col" width="75%">Tiêu đề</th>
        <th scope="col">Giá</th>
      </tr>
    </thead>
    <tbody>
      @if ($posts->perPage())
      @php
        $index = $posts->perPage() * ($posts->currentPage() - 1);
      @endphp
      @endif

      @foreach ($posts as $item)
      @php
        $meta = $item->loadMeta()->meta;
      @endphp
      <tr class="cursor-pointer" data-post-id="{{ $item->id }}">
        <th class="text-muted" scope="row">{{ $loop->iteration + $index }}</th>
        <td>
            <p class="mb-0"><i class="fa fa-file-text-o"></i> <strong>{{ $item->title }}</strong> - {{ $meta->district->district->name ?? '' }} <br>
            <span class="text-muted">
                <strong>Loại: </strong> {{ $item->categories[0]->name ?? '' }}
                - {{ $meta->province->province->name ?? 'N/a' }}
                - Ngày {{ $item->publish_at ? $item->publish_at->format('d/m/Y') : 'N/a' }}
            </span>
            </p>
        </td>
        <td><strong>{{ format_web_price($meta->price->value) ?? 'N/a' }}</strong></td>
      </tr>
      @endforeach
    </tbody>
</table>