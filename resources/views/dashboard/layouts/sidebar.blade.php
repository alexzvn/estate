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
                        <a href="#submenu" data-toggle="collapse" @active('manager.post', 'data-active="true"') aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <i data-feather="file-text"></i>
                                <span>Tin BĐS</span>
                            </div>
                            <div>
                                <i data-feather="chevron-right"></i>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled @active('manager.post', 'show')" id="submenu" data-parent="#accordionExample">
                            <li>
                                <a href="javascript:void(0);"> Tin xin phí </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">Tin cần thuê</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);"> Tin chờ duyệt </a>
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
                                <a href="javascript:void(0);"> Tất cả </a>
                            </li>
                            @foreach ($roles as $item)
                            <li>
                                <a href="javascript:void(0);"> {{ $item->name }} </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                
            </nav>

        </div>
        <!--  END SIDEBAR  -->