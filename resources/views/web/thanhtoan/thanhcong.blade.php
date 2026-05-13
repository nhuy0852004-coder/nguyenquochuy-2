@extends('web.layouts.app')

@section('tieude', 'Đặt hàng thành công')

@section('noidung')
    <div class="container">
        <section class="section-block">
            <div class="success-box">
                <div class="success-icon">
                    <i class="bi bi-check-circle"></i>
                </div>

                <h1>Đặt hàng thành công</h1>

                <p class="text-muted">
                    Cảm ơn bạn đã đặt hàng. Cửa hàng sẽ liên hệ xác nhận trong thời gian sớm nhất.
                </p>

                <div class="order-success-info">
                    <div class="summary-line">
                        <span>Mã đơn hàng</span>
                        <strong>{{ $donhang->ma_don_hang }}</strong>
                    </div>

                    <div class="summary-line">
                        <span>Người nhận</span>
                        <strong>{{ $donhang->ho_ten_nguoi_nhan }}</strong>
                    </div>

                    <div class="summary-line">
                        <span>Số điện thoại</span>
                        <strong>{{ $donhang->so_dien_thoai_nguoi_nhan }}</strong>
                    </div>

                    <div class="summary-line">
                        <span>Trạng thái</span>
                        <strong>{{ $donhang->trangThaiDonHangText() }}</strong>
                    </div>

                    <div class="summary-line">
                        <span>Thanh toán</span>
                        <strong>{{ $donhang->phuongThucThanhToanText() }}</strong>
                    </div>

                    <div class="summary-total">
                        <span>Tổng tiền</span>
                        <strong>{{ number_format($donhang->tong_tien, 0, ',', '.') }} ₫</strong>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-2 flex-wrap mt-4">
                    <a href="{{ route('web.theodoi.chitiet', $donhang->ma_don_hang) }}" class="btn-web-primary">
                        Theo dõi đơn hàng
                    </a>

                    <a href="{{ route('web.sanpham.index') }}" class="btn-web-light">
                        Tiếp tục mua hàng
                    </a>

                    <a href="{{ route('web.trangchu') }}" class="btn-web-light">
                        Về trang chủ
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection
