@extends('web.layouts.app')

@section('tieude', 'Theo dõi đơn ' . $donhang->ma_don_hang)

@section('noidung')
    <div class="container">
        <div class="breadcrumb-web">
            <a href="{{ route('web.trangchu') }}">Trang chủ</a>
            <span class="mx-2">/</span>
            <a href="{{ route('web.theodoi.index') }}">Theo dõi đơn hàng</a>
            <span class="mx-2">/</span>
            <span>{{ $donhang->ma_don_hang }}</span>
        </div>

        <section class="section-block">
            <div class="section-head">
                <div>
                    <h2>Đơn hàng {{ $donhang->ma_don_hang }}</h2>
                    <p>Đặt lúc {{ $donhang->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <span
                    class="track-status {{ $donhang->trangThaiDonHangClass() }}"
                    id="trangThaiDonHangBadge"
                >
                    {{ $donhang->trangThaiDonHangText() }}
                </span>
            </div>

            <div class="track-detail-grid">
                <div>
                    <div class="track-panel mb-3">
                        <h3>Trạng thái đơn hàng</h3>

                        <div class="order-timeline" id="timelineDonHang">
                            @foreach ($timeline as $item)
                                <div class="timeline-item {{ $item['done'] ? 'done' : '' }} {{ $item['active'] ? 'active' : '' }}">
                                    <div class="timeline-dot">
                                        @if ($item['done'])
                                            <i class="bi bi-check"></i>
                                        @endif
                                    </div>

                                    <div>
                                        <div class="timeline-title">{{ $item['label'] }}</div>
                                        <div class="timeline-desc">{{ $item['mo_ta'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="track-panel">
                        <h3>Sản phẩm trong đơn</h3>

                        @foreach ($donhang->chitietdonhang as $chitiet)
                            <div class="track-product">
                                <div class="track-product-img">
                                    @if ($chitiet->anh_san_pham)
                                        <img src="{{ asset('storage/' . $chitiet->anh_san_pham) }}" alt="{{ $chitiet->ten_san_pham }}">
                                    @else
                                        <i class="bi bi-image"></i>
                                    @endif
                                </div>

                                <div class="flex-fill">
                                    <div class="fw-bold">{{ $chitiet->ten_san_pham }}</div>
                                    <div class="text-muted small">
                                        Mã: {{ $chitiet->ma_san_pham ?: 'Đang cập nhật' }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ number_format($chitiet->don_gia, 0, ',', '.') }} ₫ x {{ $chitiet->so_luong }}
                                    </div>
                                </div>

                                <div class="fw-bold text-end">
                                    {{ number_format($chitiet->thanh_tien, 0, ',', '.') }} ₫
                                </div>
                            </div>
                        @endforeach

                        <div class="track-total-box">
                            <div class="summary-line">
                                <span>Tạm tính</span>
                                <strong>{{ number_format($donhang->tam_tinh, 0, ',', '.') }} ₫</strong>
                            </div>

                            <div class="summary-line">
                                <span>Phí vận chuyển</span>
                                <strong>
                                    @if ($donhang->phi_van_chuyen > 0)
                                        {{ number_format($donhang->phi_van_chuyen, 0, ',', '.') }} ₫
                                    @else
                                        Miễn phí
                                    @endif
                                </strong>
                            </div>

                            <div class="summary-total">
                                <span>Tổng tiền</span>
                                <strong>{{ number_format($donhang->tong_tien, 0, ',', '.') }} ₫</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="track-panel mb-3">
                        <h3>Thông tin người nhận</h3>

                        <div class="track-info-line">
                            <span>Họ tên</span>
                            <strong>{{ $donhang->ho_ten_nguoi_nhan }}</strong>
                        </div>

                        <div class="track-info-line">
                            <span>Số điện thoại</span>
                            <strong>{{ $donhang->so_dien_thoai_nguoi_nhan }}</strong>
                        </div>

                        <div class="track-info-line">
                            <span>Email</span>
                            <strong>{{ $donhang->email_nguoi_nhan ?: 'Chưa có' }}</strong>
                        </div>

                        <div class="track-info-line">
                            <span>Địa chỉ</span>
                            <strong>{{ $donhang->dia_chi_giao_hang }}</strong>
                        </div>

                        <div class="track-info-line">
                            <span>Ghi chú</span>
                            <strong>{{ $donhang->ghi_chu ?: 'Không có' }}</strong>
                        </div>
                    </div>

                    <div class="track-panel">
                        <h3>Thông tin thanh toán</h3>

                        <div class="track-info-line">
                            <span>Phương thức</span>
                            <strong>{{ $donhang->phuongThucThanhToanText() }}</strong>
                        </div>

                        <div class="track-info-line">
                            <span>Trạng thái</span>
                            <strong>{{ $donhang->trangThaiThanhToanText() }}</strong>
                        </div>

                        <div class="track-info-line">
                            <span>Mã đơn</span>
                            <strong>{{ $donhang->ma_don_hang }}</strong>
                        </div>
                    </div>

                    <div class="mt-3 d-grid gap-2">
                        <a href="{{ route('web.theodoi.index') }}" class="btn-web-light justify-content-center">
                            Tra cứu đơn khác
                        </a>

                        <a href="{{ route('web.sanpham.index') }}" class="btn-web-primary justify-content-center">
                            Tiếp tục mua hàng
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script>
        window.maDonHangTheoDoi = @json($donhang->ma_don_hang);
    </script>
@endpush
