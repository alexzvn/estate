@extends('dashboard.app')

@push('style')
<link rel="stylesheet" href="{{ asset('dashboard/assets/css/tables/table-basic.css') }}">
<link href="{{ asset('dashboard/assets/css/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/plugins/table/datatable/dt-global_style.css') }}">
<style>
    .user-active td {
        color: #1b55e2 !important;
    }

    .user-less-3-days td {
        color: #f72c38 !important;
    }
</style>
@endpush

@section('content')
<div id="tableLight" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>
                        DANH SÁCH KHÁCH HÀNG
                        @can('manager.customer.create')
                        <a href="{{ route('manager.customer.create') }}" class="btn btn-success rounded-circle"><i data-feather="plus"></i></a>
                        @endcan
                    </h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            @include('dashboard.customer.components.search')

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                    <thead>
                        <tr>
                            <th class="checkbox-column">
                                <div class="custom-control custom-checkbox checkbox-primary">
                                  <input type="checkbox" class="custom-control-input" id="check-all">
                                  <label class="custom-control-label" for="check-all"></label>
                                </div>
                            </th>
                            <th>Họ tên</th>
                            <th>Tỉnh/Vùng</th>
                            <th>Ngày đăng ký</th>
                            <th>Đã chi</th>
                            <th>Đăng ký</th>
                            <th>Hết hạn</th>
                            <th>Hđ gần nhất</th>
                            <th>CSKH</th>
                            <th style="min-width: 12%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        @php
                            // khách hoạt động: xanh nhẹ
                            // Còn 3 ngày: cam
                            // hết hạn: xan

                            $subs = $user->subscriptions->sort(function ($a, $b)
                            {
                                return $b->created_at <=> $a->created_at;
                            });

                            $sub  = $subs->sortByDesc('expires_at')->first();

                            if ($sub) {
                                if (now()->addDays(3)->lessThan($sub->expires_at)) {
                                    $class = 'user-active';
                                } elseif (now()->lessThan($sub->expires_at) && now()->addDays(3)->greaterThan($sub->expires_at)) {
                                    $class = 'user-less-3-days';
                                } else {
                                    $class = '';
                                }
                            } else {
                                $class = '';
                            }

                            $supporter = $user->supporter;

                        @endphp
                        <tr class="{{ $class }}">
                            <td class="checkbox-column">
                                <div class="custom-control custom-checkbox checkbox-primary">
                                  <input type="checkbox" class="custom-control-input phone" id="phone-{{ $loop->index }}" name="phone[]" value="{{ $user->phone }}">
                                  <label class="custom-control-label" for="phone-{{ $loop->index }}">{{ $loop->index }}</label>
                                </div>
                            </td>
                            <td @if(($supporter && $supporter->id == Auth::id()) || Auth::user()->can('manager.user.assign.customer')) class="cursor-pointer open-user" data-id="{{ $user->id }}" @endIf style="font-weight: bold">
                                {{ $user->name }} @if($user->hasVerifiedPhone()) <i class="text-success" width="15" height="15" data-feather="check-circle"></i>
                                @endif
                                <br>
                                @if (($supporter && $supporter->id == Auth::id()) || Auth::user()->can('manager.user.assign.customer'))
                                    {{ $user->phone }}
                                @else
                                    {{ hide_phone($user->phone) }}
                                @endif
                            </td>
                            <td>
                                {!! $user->provinces->map(fn($province) => $province->name)->join('<br />') !!}
                            </td>
                            <td>
                                {{ $user->created_at->format('d/m/Y') }} <br>
                                {{ $user->created_at->format('H:i:s') }}
                            </td>
                            <td>{{ number_format($user->orders->sum('after_discount_price')) }} đ</td>
                            <td>{{ $sub && $sub->activate_at ? $sub->activate_at->format('d/m/Y') : 'N/a' }}</td>
                            <td>{{ $sub && $sub->expires_at ? $sub->expires_at->format('d/m/Y') : 'N/a' }}</td>
                            <td>
                                <a class="text-primary" href="{{ route('manager.log') }}?phone={{ $user->phone }}">
                                    Xem: {{ $user->logs->filter(function ($log) { return $log->isViewPost(); })->count() }} tin
                                </a> <br>
                                {{ $user->logs->last() ?  $user->logs->last()->created_at->format('d/m/Y H:i:s') : '' }}
                            </td>
                            <td>
                                @if ($supporter)
                                    <span class="text-info">{{ $supporter->id == Auth::id() ? 'Bạn' : "$supporter->name" }}</span>
                                @else
                                N/a
                                @endif
                            </td>
                            <td class="text-center">
                                <ul class="table-controls">
                                    <li>
                                        @unless (empty($user->readNote()))
                                        <a class="bs-tooltip" data-html="true" title="{{ $user->readNote() }} {!! '<br>' . $user->note->updated_at !!}">
                                            <i class="text-info" data-feather="file-text"></i>
                                        </a>
                                        @else
                                        <a><i data-feather="file-text"></i></a>
                                        @endunless
                                        
                                    </li>

                                    @can('manager.customer.take')
                                        @php
                                            $canTake = empty($supporter) || Auth::user()->can('manager.user.assign.customer');
                                            $canUnTake = (isset($supporter) && $supporter->id == Auth::id()) || Auth::user()->can('manager.user.assign.customer')
                                        @endphp

                                        <li>
                                            @empty($supporter)
                                            <a @if($canTake) href="{{ route('manager.customer.take', ['id' => $user->id]) }}" @endIf>
                                                <i @if($canTake) class="text-success" @endIf data-feather="target"></i>
                                            </a>
                                            @else
                                            <a @if($canUnTake) href="{{ route('manager.customer.untake', ['id' => $user->id]) }}" @endIf>
                                                <i @if($canUnTake) class="text-warning" @endIf data-feather="target"></i>
                                            </a>
                                            @endempty
                                        </li>
                                    @endcan

                                    @can('manager.subscription.lock')
                                        <li>
                                            @if ($user->isBanned())
                                                <a href="{{ route('manager.customer.pardon', ['id' => $user->id]) }}"><i class="text-danger" data-feather="lock"></i></a>
                                            @else
                                                <a href="{{ route('manager.customer.ban', ['id' => $user->id]) }}"><i data-feather="unlock"></i></a>
                                            @endif
                                        </li>
                                    @endcan
                                    @if (Auth::user()->can('manager.customer.logout'))
                                        @php
                                            $isOnline = $user->isOnline();
                                        @endphp
                                        <li>
                                            <a href="{{ $isOnline ? route('manager.customer.logout', ['id' => $user->id]) : 'javascript:void(0)' }}">
                                                <i class="{{ $isOnline ? 'text-danger' : '' }}" data-feather="log-out"></i>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="btn-group mb-4 mr-2" role="group">
                    <button id="btndefault" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Hành động 
                        <i data-feather="chevron-down"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btndefault">
                        <a id="send-sms" href="javascript:void(0)">Gửi tin nhắn</a>
                    </div>
                </div>

                <span class="ml-2">Tìm thấy {{ number_format($users->total()) }} kết quả</span>

                <div class="d-flex justify-content-center">
                    {{ $users->onEachSide(0)->withQueryString()->render() }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('dashboard/assets/js/elements/tooltip.js') }}"></script>
<script>
    $('#check-all').click(function () {
        const checked = $('#check-all').prop('checked');
        $('.phone').prop('checked', checked);
    });

    $('#send-sms').click(() => {
        const phones = []

        $('.phone').each(function () {
            phones.push($(this).val())
        })

        const uri = phones.map((phone) => `recipients[]=${phone}`).join('&');

        location.href = "{{ route('manager.sms.template') }}?" + uri
    })

    $(document).ready(function () {
        $('.open-user').on('click', function () {
            let id = $(this).data('id');

            window.open(`/manager/customer/${id}/view`)
        });

        $('.bs-tooltip').tooltip();
    });
</script>
@endpush
