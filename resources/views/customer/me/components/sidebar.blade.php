<div class="col-lg-3 col-md-4">
    <div class="wrap-info card">
        <div class="wrap-avatar">
            <img class="w-100 h-100" src="{{ $customer->avatar ?? 'https://media.laodong.vn/Storage/NewsPortal/2020/8/4/825080/Amee.jpg' }}"
                alt="">
            <div class="overlay-change-avatar">
                <div>
                    <i class="fa fa-camera" aria-hidden="true"></i>
                </div>
                <div style="margin-top: -5px;"> 
                    <span>cập nhật</span>
                </div>
            </div>
        </div>
       <p class="text-center mt-3"><strong>{{ $customer->name }}</strong>
       </p>
        <div class="text-center wrap-avatar-info">
            <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a><br>
            <a href="tel:{{ $customer->phone }}">{{ $customer->phone }}</a>
        </div>
       <div class="text-center ">
        <span class="user-status-noti text-light rounded bg-success">Hoạt động</span>
       </div>
    </div>

    <div class="list-group mb-4">
        <a href="#" class="list-group-item list-group-item-action active">Tài khoản</a>
        <a href="#" class="list-group-item list-group-item-action">Đơn hàng</a>
        <a href="#" class="list-group-item list-group-item-action">Danh sách gói đăng ký</a>
        <a href="#" class="list-group-item list-group-item-action">Lịch sử truy cập</a>
        <a href="#" class="list-group-item list-group-item-action">...</a>
    </div>
</div>