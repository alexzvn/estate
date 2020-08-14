@if ($status == 0)
    <span class="badge badge-dark">Bản nháp</span>
@endif
@if ($status == 1)
    <span class="badge badge-info">Chờ duyệt</span>
@endif
@if ($status == 2)
    <span class="badge badge-success">Đã đăng</span>
@endif
@if ($status == 3)
    <span class="badge badge-primary">Bị khóa</span>
@endif