@extends('dashboard.app')

@section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>
                        Danh sách bài viết
                        <a href="{{ route('manager.post.create') }}" class="btn btn-success rounded-circle"><i data-feather="plus"></i></a>
                    </h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-hover table-light mb-4">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Tiêu đề</th>
                            <th>Thành phố</th>
                            <th>Danh mục</th>
                            <th>Số điện thoại</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                        @php
                            $meta = $post->loadMeta()->meta;
                        @endphp
                        <tr>
                            <td>{{ $loop->index }}</td>
                            <td style="font-weight: bold">{{ $post->title }}</td>
                            <td>{{ $meta->province->province->name ?? 'N/a' }}</td>
                            <td>{{ $post->categories[0]->name ?? 'N/a' }}</td>
                            <td>{{ $meta->phone->value ?? 'N/a' }}</td>
                            <td>
                                <a href="{{ route('manager.post.view', ['id' => $post->id]) }}">
                                    <i class="role-edit t-icon t-hover-icon" data-feather="edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {!! $posts->appends($_GET)->render() !!}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection