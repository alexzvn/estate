@extends('dashboard.app')

@push('style')
<link rel="stylesheet" href="{{ asset('dashboard/assets/css/tables/table-basic.css') }}">
@endpush

@section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>
                        Lịch sử tin bị báo môi giới
                    </h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <form id="search-form" action="" method="GET">
                <div class="row">
                    <div class="col-md-5 pl-md-0 order-first">
                        <div class="form-row">
                            <label for="phone" class="col-md-3 col-form-label text-md-right d-none d-md-block"><strong>Tìm kiếm: </strong></label>
            
                            <div class="col-md-9">
                                <div class="form-group input-group-sm">
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ request('phone') }}" placeholder="Tìm theo sđt">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 pl-md-0 order-md-first order-last">
                        <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
                        </a>
                    </div>
            
                </div>
            </form>

            <div class="table-responsive">

                <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Người báo</th>
                            <th>Tiêu đề tin</th>
                            <th>SĐT tin</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                        @if ($report->post === null)
                            @php
                                $report->delete();
                            @endphp
                            @continue
                        @endif
                        @php
                            $meta = $report->post->loadMeta()->meta;
                        @endphp
                        <tr>
                            <td class="text-center" >{{ $loop->index }}</td>
                            <td style="font-weight: bold">{{ $report->user->name }} <br> {{ $report->user->phone }}</td>
                            <td>{{ $report->post->title }} <a class="text-info" target="_blank" href="{{ route('manager.post.view', ['id' => $report->post->id]) }}"><i data-feather="external-link"></i></a></td>
                            <td>
                                {{ $meta->phone->value ?? 'N/a' }}
                                @isset($meta->phone->value)
                                    <a class="text-info" target="_blank" href="{{ route('manager.post') }}?query={{ $meta->phone->value }}"><i data-feather="external-link"></i></a>
                                @endisset
                            </td>
                            <td>{{ $report->created_at->diffForHumans() }}</td>
                            <td> @include('dashboard.post.components.status', ['status' => $report->post->status]) </td>
                            <td class="text-center">
                                <ul class="table-controls">
                                    @can('manager.post.report.delete')
                                    <li>
                                        <a  href="{{ route('manager.report.delete', ['id' => $report->id]) }}">
                                            <i class="text-danger" data-feather="trash-2"></i>
                                        </a>
                                    </li>
                                    @endcan
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {!! $reports->appends($_GET)->render() !!}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection