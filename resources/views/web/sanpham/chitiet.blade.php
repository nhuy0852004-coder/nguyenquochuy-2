@extends('web.layouts.app')

@section('tieude', $sanpham->ten_san_pham)

@section('mota', $sanpham->mo_ta_ngan ?: 'Chi tiết sản phẩm tại ' . ($caidatcuahang->ten_cua_hang ?? 'cửa hàng'))

@section('canonical', route('web.sanpham.chitiet', $sanpham->duong_dan))

@section('og_type', 'product')

@section('og_title', $sanpham->ten_san_pham . ' - ' . ($caidatcuahang->ten_cua_hang ?? 'Bán Hàng Việt'))

@section('og_description', $sanpham->mo_ta_ngan ?: 'Xem chi tiết sản phẩm, giá bán, tồn kho và thêm vào giỏ hàng.')

@if ($sanpham->anh_dai_dien)
    @section('og_image', asset('storage/' . $sanpham->anh_dai_dien))
@endif

@section('noidung')
    <div class="container">
        <div class="breadcrumb-web">
            <a href="{{ route('web.trangchu') }}">Trang chủ</a>
            <span class="mx-2">/</span>
            <a href="{{ route('web.sanpham.index') }}">Sản phẩm</a>
            <span class="mx-2">/</span>
            <span>{{ $sanpham->ten_san_pham }}</span>
        </div>

        <section class="product-detail-section">
            <div class="product-detail-box product-detail-pro sanpham-card-bay">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <div class="product-detail-gallery">
                            <div class="product-detail-main-image">
                                @if ($sanpham->anh_dai_dien)
                                    <img
                                        src="{{ asset('storage/' . $sanpham->anh_dai_dien) }}"
                                        alt="{{ $sanpham->ten_san_pham }}"
                                        class="anh-bay-gio"
                                        loading="eager"
                                    >
                                @else
                                    <div class="product-image-empty">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="product-detail-note">
                                <i class="bi bi-info-circle"></i>
                                Hình ảnh sản phẩm có thể chênh lệch nhẹ tùy ánh sáng và thiết bị hiển thị.
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="product-detail-info product-detail-info-pro">
                            <div class="product-detail-category">
                                {{ $sanpham->danhmuc?->ten_danh_muc ?? 'Sản phẩm' }}
                            </div>

                            <h1>{{ $sanpham->ten_san_pham }}</h1>

                            <div class="product-detail-meta">
                                @if ($sanpham->ma_san_pham)
                                    <span>Mã sản phẩm: <strong>{{ $sanpham->ma_san_pham }}</strong></span>
                                @endif

                                <span>
                                    Tình trạng:
                                    @if ($sanpham->so_luong_ton > 0)
                                        <strong class="text-success">Còn hàng</strong>
                                    @else
                                        <strong class="text-danger">Hết hàng</strong>
                                    @endif
                                </span>
                            </div>

                            <div class="detail-price detail-price-pro">
                                @if ($sanpham->gia_khuyen_mai)
                                    @php
                                        $phanTramGiam = $sanpham->gia_ban > 0
                                            ? round((($sanpham->gia_ban - $sanpham->gia_khuyen_mai) / $sanpham->gia_ban) * 100)
                                            : 0;
                                    @endphp

                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="price-sale detail-price-main">
                                            {{ number_format($sanpham->gia_khuyen_mai, 0, ',', '.') }} ₫
                                        </span>

                                        <span class="price-old">
                                            {{ number_format($sanpham->gia_ban, 0, ',', '.') }} ₫
                                        </span>

                                        <span class="detail-sale-percent">
                                            -{{ $phanTramGiam }}%
                                        </span>
                                    </div>
                                @else
                                    <span class="price-normal detail-price-main">
                                        {{ number_format($sanpham->gia_ban, 0, ',', '.') }} ₫
                                    </span>
                                @endif
                            </div>

                            @if ($sanpham->mo_ta_ngan)
                                <p class="product-detail-short-desc">
                                    {{ $sanpham->mo_ta_ngan }}
                                </p>
                            @endif

                            <div class="product-detail-stock-box">
                                @if ($sanpham->so_luong_ton > 0)
                                    <div class="stock-badge stock-ok">
                                        <i class="bi bi-check-circle"></i>
                                        Còn {{ $sanpham->so_luong_ton }} sản phẩm
                                    </div>
                                @else
                                    <div class="stock-badge stock-out">
                                        <i class="bi bi-x-circle"></i>
                                        Sản phẩm đã hết hàng
                                    </div>
                                @endif
                            </div>

                            @if ($sanpham->so_luong_ton > 0)
                                <div class="quantity-box quantity-box-pro">
                                    <span class="fw-bold">Số lượng</span>

                                    <div class="quantity-control">
                                        <button type="button" onclick="giamSoLuong()">-</button>
                                        <input type="text" id="soLuong" value="1" readonly>
                                        <button type="button" onclick="tangSoLuong({{ $sanpham->so_luong_ton }})">+</button>
                                    </div>
                                </div>

                                <div class="product-detail-actions">
                                    <form action="{{ route('web.giohang.them', $sanpham) }}" method="POST" class="form-them-gio product-detail-cart-form">
                                        @csrf
                                        <input type="hidden" name="so_luong" id="soLuongSubmit" value="1">

                                        <button type="submit" class="btn-web-primary">
                                            <i class="bi bi-bag-plus"></i>
                                            Thêm vào giỏ
                                        </button>
                                    </form>

                                    <form action="{{ route('web.giohang.them', $sanpham) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="so_luong" id="soLuongMuaNgay" value="1">
                                        <input type="hidden" name="mua_ngay" value="1">

                                        <button type="submit" class="btn-buy-now">
                                            <i class="bi bi-lightning-charge"></i>
                                            Mua ngay
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="product-detail-actions">
                                    <button type="button" class="btn-web-light w-100" disabled>
                                        Sản phẩm đã hết hàng
                                    </button>
                                </div>
                            @endif

                            <div class="product-trust-grid">
                                <div class="product-trust-item">
                                    <i class="bi bi-truck"></i>
                                    <div>
                                        <strong>Giao hàng toàn quốc</strong>
                                        <span>Nhận hàng tại nhà</span>
                                    </div>
                                </div>

                                <div class="product-trust-item">
                                    <i class="bi bi-cash-coin"></i>
                                    <div>
                                        <strong>Thanh toán COD</strong>
                                        <span>Thanh toán khi nhận hàng</span>
                                    </div>
                                </div>

                                <div class="product-trust-item">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <div>
                                        <strong>Đổi trả 7 ngày</strong>
                                        <span>Hỗ trợ nếu sản phẩm lỗi</span>
                                    </div>
                                </div>

                                <div class="product-trust-item">
                                    <i class="bi bi-headset"></i>
                                    <div>
                                        <strong>Hỗ trợ nhanh</strong>
                                        <span>Hotline/Zalo cửa hàng</span>
                                    </div>
                                </div>
                            </div>

                            <div class="product-policy-box">
                                <div>
                                    <i class="bi bi-shield-check"></i>
                                    Sản phẩm được kiểm tra trước khi giao.
                                </div>

                                <div>
                                    <i class="bi bi-box-seam"></i>
                                    Đóng gói cẩn thận, phù hợp vận chuyển xa.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="detail-description detail-description-pro">
                <div class="detail-description-head">
                    <h2>Mô tả sản phẩm</h2>
                    <p>Thông tin chi tiết giúp bạn hiểu rõ hơn về sản phẩm.</p>
                </div>

                <div class="detail-description-content">
                    @if ($sanpham->mo_ta_chi_tiet)
                        {!! nl2br(e($sanpham->mo_ta_chi_tiet)) !!}
                    @else
                        <p class="text-muted mb-0">
                            Sản phẩm chưa có mô tả chi tiết.
                        </p>
                    @endif
                </div>
            </div>
        </section>

        @if ($sanphamlienquan->count())
            <section class="section-block">
                <div class="section-head">
                    <div>
                        <h2>Sản phẩm liên quan</h2>
                        <p>Các sản phẩm cùng danh mục có thể bạn sẽ thích.</p>
                    </div>

                    <a href="{{ route('web.sanpham.index', ['danh_muc' => $sanpham->danhmuc?->duong_dan]) }}" class="btn-web-light">
                        Xem thêm
                    </a>
                </div>

                <div class="product-grid">
                    @foreach ($sanphamlienquan as $sanphamLienQuan)
                        @include('web.components.the-san-pham', ['sanpham' => $sanphamLienQuan])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function capNhatSoLuongSubmit() {
            const hienThi = document.getElementById('soLuong');
            const submitThemGio = document.getElementById('soLuongSubmit');
            const submitMuaNgay = document.getElementById('soLuongMuaNgay');

            if (hienThi && submitThemGio) {
                submitThemGio.value = hienThi.value;
            }

            if (hienThi && submitMuaNgay) {
                submitMuaNgay.value = hienThi.value;
            }
        }

        function giamSoLuong() {
            const input = document.getElementById('soLuong');
            let soLuong = parseInt(input.value);

            if (soLuong > 1) {
                input.value = soLuong - 1;
                capNhatSoLuongSubmit();
            }
        }

        function tangSoLuong(tonKho) {
            const input = document.getElementById('soLuong');
            let soLuong = parseInt(input.value);

            if (soLuong < tonKho) {
                input.value = soLuong + 1;
                capNhatSoLuongSubmit();
            }
        }
    </script>
@endpush
