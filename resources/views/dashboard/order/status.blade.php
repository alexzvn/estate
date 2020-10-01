<?php use App\Models\Order; ?>

@if ($status == Order::PAID)
    <span class="badge badge-success">Đã thanh toán</span>
@else
    <span class="badge badge-secondary">Chưa thanh toán</span>
@endif