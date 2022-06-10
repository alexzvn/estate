<?php
use App\Enums\PostType;
use App\Enums\PostSource;
?>
<!--  BEGIN SIDEBAR  -->
        
        <div class="sidebar-wrapper sidebar-theme">
            
            <nav id="sidebar">
                <div class="shadow-bottom"></div>

                <ul class="list-unstyled menu-categories" id="accordionExample">

                    <li class="menu">
                        <a href="{{ route('manager') }}" @active('manager', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i data-feather="home"></i> <span>Trang chủ</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu">
                        <a href="/" target="_blank" aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i data-feather="home"></i> <span>Trang khách hàng</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu">
                        <a href="{{ route('manager.chat') }}" aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i data-feather="message-square"></i> <span>Hỗ trợ trực tuyến</span>
                            </div>
                        </a>
                    </li>

                    @can('manager.category.view')
                    <li class="menu">
                        <a href="{{ route('manager.category') }}" @active('manager.category', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i data-feather="bookmark"></i> <span>Quản lý danh mục</span>
                            </div>
                        </a>
                    </li>
                    @endcan

                    @can('manager.post.online.view')
                    @php
                        $active = request()->is('manager/post/online*');
                    @endphp
                    <li class="menu">
                        <a href="#online-post" data-toggle="collapse" @active($active, 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="file-text"></i>
                                <span>Tin Online</span>
                            </div>
                            <div>
                                <i data-feather="chevron-right"></i>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled @active($active, 'show')" id="online-post" data-parent="#accordionExample">
                            @php
                                $online = route('manager.post.online');
                            @endphp
                            <li class="@active('manager.post.online')">
                                <a href="{{ $online }}"> Tất cả </a>
                            </li>
                            <li class="@active(request('source', -1) == PostSource::TinChinhChu)">
                                <a href="{{ "$online?source=" . PostSource::TinChinhChu }}">Tin chính chủ</a>
                            </li>
                            <li class="@active(request('source') == PostSource::LocTinBds)">
                                <a href="{{ "$online?source=" . PostSource::LocTinBds }}">Lọc tin BDS</a>
                            </li>
                            <li class="@active(request('source') == PostSource::ChoTot)">
                                <a href="{{ "$online?source=" . PostSource::ChoTot }}">Tin chợ tốt</a>
                            </li>
                            <li class="@active('manager.post.online.trashed')">
                                <a href="{{ route('manager.post.online.trashed') }}">Tin đã xóa </a>
                            </li>
                        </ul>
                    </li>
                    @endcan

                    @can('manager.post.fee.view')
                    @php
                        $active = request()->is('manager/post/fee*');
                    @endphp
                    <li class="menu">
                        <a href="#fee-post" data-toggle="collapse" @active($active, 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="file-text"></i>
                                <span>Tin xác thực</span>
                            </div>
                            <div>
                                <i data-feather="chevron-right"></i>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled @active($active, 'show')" id="fee-post" data-parent="#accordionExample">
                            <li class="@active('manager.post.fee')">
                                <a href="{{ route('manager.post.fee') }}"> Tất cả </a>
                            </li>
                            <li class="@active('manager.post.fee.trashed')">
                                <a href="{{ route('manager.post.fee.trashed') }}">Tin đã xóa </a>
                            </li>
                        </ul>
                    </li>
                    @endcan

                    @can('manager.post.market.view')
                    @php
                        $active = request()->is('manager/post/market*');
                    @endphp
                    <li class="menu">
                        <a href="#market-post" data-toggle="collapse" @active($active, 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="file-text"></i>
                                <span>Tin thị trường</span>
                            </div>
                            <div>
                                <i data-feather="chevron-right"></i>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled @active($active, 'show')" id="market-post" data-parent="#accordionExample">
                            <li class="@active('manager.post.market')">
                                <a href="{{ route('manager.post.market') }}"> Tất cả </a>
                            </li>
                            {{-- <li class="@active('manager.post.market.trashed')">
                                <a href="{{ route('manager.post.market.trashed') }}">Tin đã xóa </a>
                            </li> --}}
                        </ul>
                    </li>
                    @endcan

                    @can('manager.censorship.view')
                    <li class="menu">
                        <a href="{{ route('manager.censorship') }}" @active('manager.censorship', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="file-text"></i>
                                <span>Kiểm duyệt tin</span>
                            </div>
                        </a>
                    </li>
                    @endcan

                    @can('manager.post.report.view')
                    <li class="menu">
                        <a href="{{ route('manager.report.view') }}" @active('manager.report.view', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="alert-octagon"></i>
                                <span>Tin báo môi giới</span>
                            </div>
                        </a>
                    </li>
                    @endcan

                    @can('manager.role.view')
                    <li class="menu">
                        <a href="{{ route('manager.role') }}" @active('manager.role', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="award"></i>
                                <span>Vai trò</span>
                            </div>
                        </a>
                    </li>
                    @endcan

                    @can('manager.user.view')
                    <li class="menu">
                        <a href="#menu-user" @active('manager.user', 'data-active="true"') data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="users"></i>
                                <span> Thành viên</span>
                            </div>
                            <div>
                                <i data-feather="chevron-right"></i>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled @active('manager.user', 'show')" id="menu-user" data-parent="#accordionExample">
                            <li>
                                <a href="{{ route('manager.user') }}"> Tất cả </a>
                            </li>
                            @foreach ($roles->where('customer', '<>', true) as $item)
                            <li>
                                <a href="{{ route('manager.user') . "?roles=$item->id" }}"> {{ $item->name }} </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endcan

                    @can('manager.customer.view')
                    <li class="menu">
                        <a href="#menu-customer" @active('manager.customer*', 'data-active="true"') data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="users"></i>
                                <span> Khách hàng</span>
                            </div>
                            <div>
                                <i data-feather="chevron-right"></i>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled @active('manager.customer*', 'show')" id="menu-customer" data-parent="#accordionExample">
                            <li>
                                <a href="{{ route('manager.customer') }}"> Tất cả </a>
                            </li>
                            @foreach ($roles->where('customer', true) as $item)
                            <li>
                                <a href="{{ route('manager.customer') . "?roles=$item->id" }}"> {{ $item->name }} </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endcan

                    @can('manager.customer.log.view')
                    <li class="menu">
                        <a href="{{ route('manager.log') }}" @active('manager.log', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i data-feather="activity"></i> <span>Hoạt động khách hàng</span>
                            </div>
                        </a>
                    </li>
                    @endcan

                    @can('manager.note.user.view')
                    <li class="menu">
                        <a href="{{ route('manager.note.user') }}" @active('manager.note.user', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i data-feather="file"></i> <span>Lịch sử ghi chú</span>
                            </div>
                        </a>
                    </li>
                    @endcan

                    @can('manager.audit.view')
                    <li class="menu">
                        <a href="{{ route('manager.audit') }}" @active('manager.audit', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i data-feather="activity"></i> <span>Audit Log</span>
                            </div>
                        </a>
                    </li>
                    @endcan

                    @can('manager.order.view')
                    <li class="menu">
                        <a href="{{ route('manager.order') }}" @active('manager.order', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i data-feather="credit-card"></i> <span>Quản lý đơn hàng</span>
                            </div>
                        </a>
                    </li>
                    @endcan

                    @can('manager.plan.view')
                    <li class="menu">
                        <a href="{{ route('manager.plan') }}" @active('manager.plan*', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i data-feather="package"></i> <span>Các gói đăng ký</span>
                            </div>
                        </a>
                    </li>
                    @endcan

                    @can('*')
                    <li class="menu">
                        <a href="{{ route('manager.keyword') }}" @active('manager.keyword*', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="flag"></i>
                                <span>Chặn từ khóa</span>
                            </div>
                        </a>
                    </li>
                    @endcan

                    @can('blacklist.phone.view')
                    <li class="menu">
                        <a href="{{ route('manager.blacklist.phone') }}" @active('manager.blacklist.phone', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="phone"></i>
                                <span>Chặn số điện thoại</span>
                            </div>
                        </a>
                    </li>
                    @endcan

                    @can('whitelist.phone.view')
                    <li class="menu">
                        <a href="{{ route('manager.whitelist.phone') }}" @active('manager.whitelist.phone', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="phone"></i>
                                <span>Điện thoại trắng</span>
                            </div>
                        </a>
                    </li>
                    @endcan

                    {{-- @can('manager.sms')
                    <li class="menu">
                        <a href="{{ route('manager.sms.template') }}" @active('manager.sms.template', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="mail"></i>
                                <span>Quản lý SMS</span>
                            </div>
                        </a>
                    </li>
                    @endcan --}}

                    @can('manager.site.setting')
                    <li class="menu">
                        <a href="{{ route('manager.setting') }}" @active('manager.setting*', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i data-feather="settings"></i> <span>Cài đặt</span>
                            </div>
                        </a>
                    </li>
                    @endcan


                </ul>
                
            </nav>

        </div>
        <!--  END SIDEBAR  -->
