@extends('web.layouts.app')

@section('tieude', 'Kết quả tra cứu đơn hàng')

@section('noidung')
    <div class="container">
        <div class="breadcrumb-web">
            <a href="{{ route('web.trangchu') }}">Trang chủ</a>
            <span class="mx-2">/</span>
            <a href="{{ route('web.theodoi.index') }}">Theo dõi đơn hàng</a>
            <span class="mx-2">/</span>
            <span>Kết quả tra cứu</span>
        </div>

        <section class="section-block">
            <div class="section-head">
                <div>
                    <h2>Kết quả tra cứu</h2>
                    <p>
                        Từ khóa:
                        <strong>{{ $tukhoa }}</strong>
                    </p>
                </div>

                <a href="{{ route('web.theodoi.index') }}" class="btn-web-light">
                    Tra cứu lại
                </a>
            </div>

            @if ($danhsachdonhang->count())
                <div class="track-result-list">
                    @foreach ($danhsachdonhang as $donhang)
                        <div class="track-order-card">
                            <div class="track-order-main">
                                <div>
                                    <div class="track-order-code">{{ $donhang->ma_don_hang }}</div>
                                    <div class="text-muted small">
                                        Đặt lúc {{ $donhang->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>

                                <span class="track-status {{ $donhang->trangThaiDonHangClass() }}">
                                    {{ $donhang->trangThaiDonHangText() }}
                                </span>
                            </div>

                            <div class="track-order-info">
                                <div>
                                    <span>Người nhận</span>
                                    <strong>{{ $donhang->ho_ten_nguoi_nhan }}</strong>
                                </div>

                                <div>
                                    <span>Số điện thoại</span>
                                    <strong>{{ $donhang->so_dien_thoai_nguoi_nhan }}</strong>
                                </div>

                                <div>
                                    <span>Số sản phẩm</span>
                                    <strong>{{ $donhang->chitietdonhang->sum('so_luong') }}</strong>
                                </div>

                                <div>
                                    <span>Tổng tiền</span>
                                    <strong class="text-danger">
                                        {{ number_format($donhang->tong_tien, 0, ',', '.') }} ₫
                                    </strong>
                                </div>
                            </div>

                            <div class="track-order-actions">
                                <a href="{{ route('web.theodoi.chitiet', $donhang->ma_don_hang) }}" class="btn-web-primary">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-web">
                    <div class="empty-web-icon">
                        <i class="bi bi-search"></i>
                    </div>

                    <h5 class="fw-bold">Không tìm thấy đơn hàng</h5>

                    <p class="text-muted mb-3">
                        Vui lòng kiểm tra lại mã đơn hàng hoặc số điện thoại đã nhập.
                    </p>

                    <a href="{{ route('web.theodoi.index') }}" class="btn-web-primary">
                        Tra cứu lại
                    </a>
                </div>
            @endif
        </section>
    </div>
@endsection
