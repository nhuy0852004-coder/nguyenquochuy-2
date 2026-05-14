@extends('web.layouts.app')

@section('tieude', 'Trang chủ')

@section('mota', 'Mua sắm thời trang Việt Nam với sản phẩm mới, giá tốt, giao hàng toàn quốc và thanh toán khi nhận hàng.')

@section('canonical', route('web.trangchu'))

@section('og_title', ($caidatcuahang->ten_cua_hang ?? 'Bán Hàng Việt') . ' - Thời trang dễ mặc, giá tốt mỗi ngày')

@section('og_description', 'Khám phá sản phẩm thời trang mới, khuyến mãi, giao hàng toàn quốc và hỗ trợ theo dõi đơn hàng.')

@section('noidung')
    <section class="home-hero">
        <div class="container">
            <div class="home-hero-box">
                <div class="row g-0 align-items-center">
                    <div class="col-lg-7">
                        <div class="home-hero-content">
                            <div class="home-hero-label">
                                <i class="bi bi-stars"></i>
                                Bộ sưu tập mới đã lên kệ
                            </div>

                            <h1>Thời trang dễ mặc, giá tốt mỗi ngày</h1>

                            <p>
                                Khám phá các mẫu áo quần, giày dép và phụ kiện mới nhất.
                                Giao hàng toàn quốc, thanh toán khi nhận hàng, hỗ trợ đổi trả rõ ràng.
                            </p>

                            <div class="home-hero-actions">
                                <a href="{{ route('web.sanpham.index') }}" class="btn-web-primary">
                                    <i class="bi bi-bag"></i>
                                    Mua sắm ngay
                                </a>

                                <a href="#sanPhamMoi" class="btn-web-light">
                                    Xem hàng mới
                                </a>
                            </div>

                            <div class="home-hero-stats">
                                <div>
                                    <strong>{{ $sanphammoi->count() }}+</strong>
                                    <span>Sản phẩm mới</span>
                                </div>

                                <div>
                                    <strong>{{ $danhsachdanhmuc->count() }}+</strong>
                                    <span>Danh mục</span>
                                </div>

                                <div>
                                    <strong>COD</strong>
                                    <span>Thanh toán khi nhận</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="home-hero-visual">
                            @php
                                $sanPhamHero = $sanphamnoibat->first() ?? $sanphammoi->first();
                            @endphp

                            @if ($sanPhamHero)
                                <div class="home-hero-product-card">
                                    <div class="home-hero-product-img">
                                        @if ($sanPhamHero->anh_dai_dien)
                                            <img src="{{ asset('storage/' . $sanPhamHero->anh_dai_dien) }}" alt="{{ $sanPhamHero->ten_san_pham }}">
                                        @else
                                            <i class="bi bi-image"></i>
                                        @endif
                                    </div>

                                    <div class="home-hero-product-body">
                                        <div class="text-muted small">
                                            {{ $sanPhamHero->danhmuc?->ten_danh_muc ?? 'Sản phẩm nổi bật' }}
                                        </div>

                                        <h3>{{ $sanPhamHero->ten_san_pham }}</h3>

                                        <div>
                                            @if ($sanPhamHero->gia_khuyen_mai)
                                                <span class="price-sale">
                                                    {{ number_format($sanPhamHero->gia_khuyen_mai, 0, ',', '.') }} ₫
                                                </span>
                                                <span class="price-old">
                                                    {{ number_format($sanPhamHero->gia_ban, 0, ',', '.') }} ₫
                                                </span>
                                            @else
                                                <span class="price-normal">
                                                    {{ number_format($sanPhamHero->gia_ban, 0, ',', '.') }} ₫
                                                </span>
                                            @endif
                                        </div>

                                        <a href="{{ route('web.sanpham.chitiet', $sanPhamHero->duong_dan) }}" class="btn-web-primary w-100 mt-3">
                                            Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="home-hero-empty">
                                    <i class="bi bi-bag-heart"></i>
                                    <strong>Thêm sản phẩm trong Admin</strong>
                                    <span>Sản phẩm nổi bật sẽ hiển thị tại đây.</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-service-strip">
        <div class="container">
            <div class="home-service-grid">
                <div class="home-service-item">
                    <div class="home-service-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div>
                        <strong>Giao hàng toàn quốc</strong>
                        <span>Nhận hàng tại nhà</span>
                    </div>
                </div>

                <div class="home-service-item">
                    <div class="home-service-icon">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <div>
                        <strong>Thanh toán COD</strong>
                        <span>Thanh toán khi nhận hàng</span>
                    </div>
                </div>

                <div class="home-service-item">
                    <div class="home-service-icon">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div>
                        <strong>Đổi trả 7 ngày</strong>
                        <span>Hỗ trợ nếu sản phẩm lỗi</span>
                    </div>
                </div>

                <div class="home-service-item">
                    <div class="home-service-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <div>
                        <strong>Hỗ trợ nhanh</strong>
                        <span>Hotline/Zalo cửa hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($danhsachdanhmuc->count())
        <section class="section-block">
            <div class="container">
                <div class="section-head">
                    <div>
                        <h2>Danh mục nổi bật</h2>
                        <p>Lựa chọn nhanh nhóm sản phẩm bạn quan tâm.</p>
                    </div>

                    <a href="{{ route('web.sanpham.index') }}" class="btn-web-light">
                        Xem tất cả
                    </a>
                </div>

                <div class="category-grid home-category-grid">
                    @foreach ($danhsachdanhmuc as $danhmuc)
                        <a href="{{ route('web.sanpham.index', ['danh_muc' => $danhmuc->duong_dan]) }}" class="category-card">
                            <div class="category-icon">
                                <i class="bi bi-tags"></i>
                            </div>

                            <div>
                                <h3>{{ $danhmuc->ten_danh_muc }}</h3>
                                <p>{{ $danhmuc->mo_ta ?: 'Xem sản phẩm trong danh mục' }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($sanphamnoibat->count())
        <section class="section-block">
            <div class="container">
                <div class="section-head">
                    <div>
                        <h2>Sản phẩm nổi bật</h2>
                        <p>Những sản phẩm được cửa hàng ưu tiên giới thiệu.</p>
                    </div>

                    <a href="{{ route('web.sanpham.index') }}" class="btn-web-light">
                        Mua sắm ngay
                    </a>
                </div>

                <div class="product-grid">
                    @foreach ($sanphamnoibat as $sanpham)
                        @include('web.components.the-san-pham', ['sanpham' => $sanpham])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="home-collection-banner">
        <div class="container">
            <div class="home-collection-box">
                <div>
                    <span class="home-collection-label">Gợi ý hôm nay</span>
                    <h2>Phối đồ đơn giản, mặc đẹp mỗi ngày</h2>
                    <p>
                        Tìm các sản phẩm basic, dễ phối và phù hợp nhiều hoàn cảnh:
                        đi học, đi làm, đi chơi hoặc dạo phố.
                    </p>
                </div>

                <a href="{{ route('web.sanpham.index') }}" class="btn-web-primary">
                    Khám phá sản phẩm
                </a>
            </div>
        </div>
    </section>

    <section class="section-block" id="sanPhamMoi">
        <div class="container">
            <div class="section-head">
                <div>
                    <h2>Sản phẩm mới</h2>
                    <p>Các sản phẩm vừa được cập nhật trên cửa hàng.</p>
                </div>

                <a href="{{ route('web.sanpham.index') }}" class="btn-web-light">
                    Xem thêm
                </a>
            </div>

            @if ($sanphammoi->count())
                <div class="product-grid">
                    @foreach ($sanphammoi as $sanpham)
                        @include('web.components.the-san-pham', ['sanpham' => $sanpham])
                    @endforeach
                </div>
            @else
                <div class="empty-web">
                    <div class="empty-web-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h5 class="fw-bold">Chưa có sản phẩm mới</h5>
                    <p class="text-muted mb-0">Vui lòng thêm sản phẩm trong trang Admin.</p>
                </div>
            @endif
        </div>
    </section>

    @if ($sanphamkhuyenmai->count())
        <section class="section-block">
            <div class="container">
                <div class="section-head">
                    <div>
                        <h2>Sản phẩm khuyến mãi</h2>
                        <p>Các sản phẩm đang có giá tốt.</p>
                    </div>

                    <a href="{{ route('web.sanpham.index', ['sap_xep' => 'gia_thap']) }}" class="btn-web-light">
                        Xem ưu đãi
                    </a>
                </div>

                <div class="product-grid">
                    @foreach ($sanphamkhuyenmai as $sanpham)
                        @include('web.components.the-san-pham', ['sanpham' => $sanpham])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($sanphambanchay->count())
        <section class="section-block">
            <div class="container">
                <div class="section-head">
                    <div>
                        <h2>Sản phẩm bán chạy</h2>
                        <p>Những sản phẩm được khách hàng lựa chọn nhiều.</p>
                    </div>

                    <a href="{{ route('web.sanpham.index') }}" class="btn-web-light">
                        Xem sản phẩm
                    </a>
                </div>

                <div class="product-grid">
                    @foreach ($sanphambanchay as $sanpham)
                        @include('web.components.the-san-pham', ['sanpham' => $sanpham])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="home-guide-section">
        <div class="container">
            <div class="section-head">
                <div>
                    <h2>Mua hàng đơn giản</h2>
                    <p>Chỉ vài bước để hoàn tất đơn hàng trên website.</p>
                </div>
            </div>

            <div class="home-guide-grid">
                <div class="home-guide-card">
                    <div class="home-guide-step">1</div>
                    <h3>Chọn sản phẩm</h3>
                    <p>Tìm kiếm, lọc danh mục và xem chi tiết sản phẩm bạn thích.</p>
                </div>

                <div class="home-guide-card">
                    <div class="home-guide-step">2</div>
                    <h3>Thêm vào giỏ</h3>
                    <p>Chọn số lượng, kiểm tra giỏ hàng và tổng tiền trước khi thanh toán.</p>
                </div>

                <div class="home-guide-card">
                    <div class="home-guide-step">3</div>
                    <h3>Đặt hàng</h3>
                    <p>Nhập thông tin nhận hàng, chọn thanh toán và theo dõi trạng thái đơn.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
