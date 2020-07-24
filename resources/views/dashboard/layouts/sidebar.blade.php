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

                    @can('manager.category.view')
                    <li class="menu">
                        <a href="{{ route('manager.category') }}" @active('manager.category', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i data-feather="bookmark"></i> <span>Quản lý danh mục</span>
                            </div>
                        </a>
                    </li>
                    @endcan

                    @can('manager.post.view')
                    <li class="menu">
                        <a href="#submenu" data-toggle="collapse" @active(request()->is('manager/post/*'), 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="file-text"></i>
                                <span>Tin BĐS</span>
                            </div>
                            <div>
                                <i data-feather="chevron-right"></i>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled @active(request()->routeIs('manager.post*'), 'show')" id="submenu" data-parent="#accordionExample">
                            @can('manager.post.create')
                            <li>
                                <a href="{{ route('manager.post.create') }}" style="color: red">Tạo tin mới</a>
                            </li>
                            @endcan
                            <li>
                                <a href="{{ route('manager.post') }}?status=2"> Tin xin phí </a>
                                {{-- Duyệt từ tin crawl --}}
                            </li>
                            <li>
                                <a href="javascript:void(0)">Cần thuê - cần mua </a>
                                {{-- Tin crawl từ trang khác xử lý sau --}}
                            </li>
                            <li>
                                <a href="javascript:void(0)">Tin thị trường</a> 
                                {{-- Tin ảnh collection --}}
                            </li>
                            <li>
                                <a href="{{ route('manager.post') }}?status=1">Tin web online </a>
                                {{-- Tin crawl từ trang khác --}}
                            </li>
                            <li>
                                <a href="{{ route('manager.post.trashed') }}">Tin đã xóa </a>
                            </li>
                        </ul>
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

                    @can('manager.plan.view')
                    <li class="menu">
                        <a href="{{ route('manager.plan') }}" @active('manager.plan*', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i data-feather="package"></i> <span>Các gói đăng ký</span>
                            </div>
                        </a>
                    </li>
                    @endcan

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