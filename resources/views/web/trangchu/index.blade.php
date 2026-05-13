@extends('web.layouts.app')

@section('tieude', 'Trang chủ')

@section('mota', 'Mua sắm thời trang Việt Nam với giao diện đẹp, giá VNĐ và thanh toán đơn giản.')

@section('noidung')
    <section class="hero-section">
        <div class="container">
            <div class="hero-box">
                <div class="row g-0 align-items-center">
                    <div class="col-lg-7">
                        <div class="hero-content">
                            <div class="hero-label">
                                <i class="bi bi-stars"></i>
                                Bộ sưu tập mới đã lên kệ
                            </div>

                            <h1>Thời trang dễ mặc, giá tốt, giao hàng toàn quốc</h1>

                            <p>
                                Khám phá các sản phẩm thời trang mới nhất với giá hiển thị rõ ràng bằng Việt Nam Đồng,
                                mua hàng nhanh và theo dõi đơn dễ dàng.
                            </p>

                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('web.sanpham.index') }}" class="btn-web-primary">
                                    <i class="bi bi-bag"></i>
                                    Mua sắm ngay
                                </a>

                                <a href="#sanPhamMoi" class="btn-web-light">
                                    Xem sản phẩm mới
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="hero-image">
                            <div class="hero-card-demo">
                                <div class="hero-card-img">
                                    <i class="bi bi-bag-heart"></i>
                                </div>
                                <div class="fw-bold">Áo thun nam basic</div>
                                <div class="text-muted small mb-2">Form đẹp, dễ phối đồ</div>
                                <div class="price-sale">149.000 ₫</div>
                            </div>
                        </div>
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

                <div class="category-grid">
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
                </div>

                <div class="product-grid">
                    @foreach ($sanphamnoibat as $sanpham)
                        @include('web.components.the-san-pham', ['sanpham' => $sanpham])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

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
                        Xem tất cả
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
@endsection
