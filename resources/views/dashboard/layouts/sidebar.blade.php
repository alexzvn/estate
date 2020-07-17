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
                        <a href="{{ route('manager.category') }}" @active('manager.category', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i data-feather="bookmark"></i> <span>Quản lý danh mục</span>
                            </div>
                        </a>
                    </li>

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
                            <li>
                                <a href="{{ route('manager.post.create') }}" style="color: red">Tạo tin mới</a>
                            </li>
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

                    <li class="menu">
                        <a href="#role" @active('manager.role', 'data-active="true"') data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="award"></i>
                                <span>Vai trò</span>
                            </div>
                            <div>
                                <i data-feather="chevron-right"></i>
                            </div>
                        </a>
                        <ul class="submenu list-unstyled collapse @active('manager.role', 'show')" id="role" data-parent="#accordionExample" style="">
                            <li class="@active('manager.role')">
                                <a href="{{ route('manager.role') }}"> Tất cả vai trò </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu">
                        <a href="#submenu2" @active('manager.user', 'data-active="true"') data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="users"></i>
                                <span> Thành viên</span>
                            </div>
                            <div>
                                <i data-feather="chevron-right"></i>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled @active('manager.user', 'show')" id="submenu2" data-parent="#accordionExample">
                            <li>
                                <a href="{{ route('manager.user') }}"> Tất cả </a>
                            </li>
                            @foreach ($roles as $item)
                            <li>
                                <a href="{{ route('manager.user') . "?roles=$item->id" }}"> {{ $item->name }} </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                
            </nav>

        </div>
        <!--  END SIDEBAR  -->