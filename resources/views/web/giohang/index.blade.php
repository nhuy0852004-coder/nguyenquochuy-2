@extends('web.layouts.app')

@section('tieude', 'Giỏ hàng')

@section('noidung')
    @php
        $mocMienPhiVanChuyen = 500000;
        $conThieuMienPhi = max(0, $mocMienPhiVanChuyen - $tongtien);
        $phanTramMienPhi = min(100, ($tongtien / $mocMienPhiVanChuyen) * 100);
    @endphp

    <div class="container">
        <div class="breadcrumb-web">
            <a href="{{ route('web.trangchu') }}">Trang chủ</a>
            <span class="mx-2">/</span>
            <span>Giỏ hàng</span>
        </div>

        <section class="section-block">
            <div class="checkout-page-head">
                <div>
                    <h1>Giỏ hàng</h1>
                    <p>Kiểm tra sản phẩm, số lượng và tổng tiền trước khi thanh toán.</p>
                </div>

                <a href="{{ route('web.sanpham.index') }}" class="btn-web-light">
                    <i class="bi bi-arrow-left"></i>
                    Tiếp tục mua hàng
                </a>
            </div>

            @if (session('thanhcong'))
                <div class="alert alert-success border-0 rounded-3">
                    {{ session('thanhcong') }}
                </div>
            @endif

            @if (session('loi'))
                <div class="alert alert-danger border-0 rounded-3">
                    {{ session('loi') }}
                </div>
            @endif

            @if (count($giohang))
                <div class="cart-free-shipping">
                    <div class="cart-free-shipping-top">
                        @if ($conThieuMienPhi > 0)
                            <div>
                                <strong>Mua thêm {{ number_format($conThieuMienPhi, 0, ',', '.') }} ₫ để được miễn phí vận chuyển</strong>
                                <span>Đơn hàng từ {{ number_format($mocMienPhiVanChuyen, 0, ',', '.') }} ₫ sẽ được miễn phí vận chuyển.</span>
                            </div>
                        @else
                            <div>
                                <strong>Bạn đã được miễn phí vận chuyển</strong>
                                <span>Đơn hàng của bạn đã đạt điều kiện miễn phí vận chuyển.</span>
                            </div>
                        @endif

                        <i class="bi bi-truck"></i>
                    </div>

                    <div class="cart-free-progress">
                        <div style="width: {{ $phanTramMienPhi }}%;"></div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <form action="{{ route('web.giohang.capnhat') }}" method="POST" class="cart-box cart-box-pro">
                            @csrf
                            @method('PATCH')

                            <div class="cart-head">
                                <div>
                                    <h5 class="fw-bold mb-1">Sản phẩm trong giỏ</h5>
                                    <span class="text-muted">{{ count($giohang) }} sản phẩm</span>
                                </div>

                                <button type="submit" class="btn-web-light">
                                    <i class="bi bi-arrow-clockwise"></i>
                                    Cập nhật
                                </button>
                            </div>

                            <div class="cart-list">
                                @foreach ($giohang as $item)
                                    <div class="cart-item cart-item-pro">
                                        <div class="cart-img">
                                            @if ($item['anh_dai_dien'])
                                                <img src="{{ asset('storage/' . $item['anh_dai_dien']) }}" alt="{{ $item['ten_san_pham'] }}">
                                            @else
                                                <i class="bi bi-image"></i>
                                            @endif
                                        </div>

                                        <div class="cart-info">
                                            <a href="{{ route('web.sanpham.chitiet', $item['duong_dan']) }}" class="cart-name">
                                                {{ $item['ten_san_pham'] }}
                                            </a>

                                            <div class="text-muted small">
                                                Mã: {{ $item['ma_san_pham'] ?? 'Đang cập nhật' }}
                                            </div>

                                            <div class="text-muted small">
                                                Tồn kho: {{ $item['ton_kho'] }}
                                            </div>

                                            <div class="cart-mobile-price">
                                                {{ number_format($item['don_gia'], 0, ',', '.') }} ₫
                                            </div>
                                        </div>

                                        <div class="cart-price">
                                            {{ number_format($item['don_gia'], 0, ',', '.') }} ₫
                                        </div>

                                        <div class="cart-qty">
                                            <input
                                                type="number"
                                                name="so_luong[{{ $item['sanpham_id'] }}]"
                                                value="{{ $item['so_luong'] }}"
                                                min="1"
                                                max="{{ $item['ton_kho'] }}"
                                            >
                                        </div>

                                        <div class="cart-total">
                                            {{ number_format($item['thanh_tien'], 0, ',', '.') }} ₫
                                        </div>

                                        <div>
                                            <button
                                                type="submit"
                                                form="formXoa{{ $item['sanpham_id'] }}"
                                                class="cart-remove"
                                                title="Xóa sản phẩm"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="cart-actions cart-actions-pro">
                                <a href="{{ route('web.sanpham.index') }}" class="btn-web-light">
                                    <i class="bi bi-arrow-left"></i>
                                    Tiếp tục mua hàng
                                </a>

                                <button type="submit" class="btn-web-primary">
                                    <i class="bi bi-arrow-clockwise"></i>
                                    Cập nhật giỏ hàng
                                </button>
                            </div>
                        </form>

                        @foreach ($giohang as $item)
                            <form
                                id="formXoa{{ $item['sanpham_id'] }}"
                                action="{{ route('web.giohang.xoa', $item['sanpham_id']) }}"
                                method="POST"
                                class="d-none"
                                data-confirm-title="Xóa sản phẩm?"
                                data-confirm="Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng không?"
                                data-confirm-button="Xóa"
                                data-confirm-icon="warning"
                            >
                                @csrf
                                @method('DELETE')
                            </form>
                        @endforeach
                    </div>

                    <div class="col-lg-4">
                        <div class="cart-summary cart-summary-pro">
                            <h5 class="fw-bold mb-3">Tóm tắt đơn hàng</h5>

                            <div class="summary-line">
                                <span>Tạm tính</span>
                                <strong>{{ number_format($tongtien, 0, ',', '.') }} ₫</strong>
                            </div>

                            <div class="summary-line">
                                <span>Phí vận chuyển</span>
                                @if ($tongtien >= $mocMienPhiVanChuyen)
                                    <strong class="text-success">Miễn phí</strong>
                                @else
                                    <span class="text-muted">Tính ở bước thanh toán</span>
                                @endif
                            </div>

                            <div class="summary-line">
                                <span>Ưu đãi</span>
                                <span class="text-muted">Chưa áp dụng</span>
                            </div>

                            <div class="summary-total">
                                <span>Tổng tạm tính</span>
                                <strong>{{ number_format($tongtien, 0, ',', '.') }} ₫</strong>
                            </div>

                            <a href="{{ route('web.thanhtoan.index') }}" class="btn-web-primary w-100 justify-content-center mt-3">
                                <i class="bi bi-credit-card"></i>
                                Thanh toán
                            </a>

                            <form
                                action="{{ route('web.giohang.xoatatca') }}"
                                method="POST"
                                class="mt-2"
                                data-confirm-title="Xóa toàn bộ giỏ hàng?"
                                data-confirm="Tất cả sản phẩm trong giỏ hàng sẽ bị xóa."
                                data-confirm-button="Xóa tất cả"
                                data-confirm-icon="warning"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-web-light w-100 justify-content-center">
                                    Xóa toàn bộ giỏ hàng
                                </button>
                            </form>

                            <div class="cart-summary-note">
                                <div>
                                    <i class="bi bi-shield-check"></i>
                                    Đơn hàng được kiểm tra trước khi xử lý.
                                </div>

                                <div>
                                    <i class="bi bi-truck"></i>
                                    Giao hàng toàn quốc, hỗ trợ COD.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-web cart-empty-pro">
                    <div class="empty-web-icon">
                        <i class="bi bi-bag"></i>
                    </div>
                    <h5 class="fw-bold">Giỏ hàng đang trống</h5>
                    <p class="text-muted mb-3">
                        Hãy thêm sản phẩm vào giỏ hàng để bắt đầu đặt hàng.
                    </p>
                    <a href="{{ route('web.sanpham.index') }}" class="btn-web-primary">
                        Xem sản phẩm
                    </a>
                </div>
            @endif
        </section>
    </div>
@endsection
