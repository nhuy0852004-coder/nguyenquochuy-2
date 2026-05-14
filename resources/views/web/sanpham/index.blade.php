@extends('web.layouts.app')

@section('tieude', 'Sản phẩm')

@section('mota', 'Danh sách sản phẩm thời trang, giá Việt Nam Đồng, có tìm kiếm, lọc danh mục, lọc giá và sắp xếp dễ dàng.')

@section('canonical', route('web.sanpham.index'))

@section('og_title', 'Sản phẩm - ' . ($caidatcuahang->ten_cua_hang ?? 'Bán Hàng Việt'))

@section('og_description', 'Khám phá danh sách sản phẩm thời trang mới nhất, giá tốt và dễ dàng thêm vào giỏ hàng.')

@section('noidung')
    <div class="container">
        <div class="breadcrumb-web">
            <a href="{{ route('web.trangchu') }}">Trang chủ</a>
            <span class="mx-2">/</span>
            <span>Sản phẩm</span>
        </div>

        <section class="section-block">
            <div class="product-page-head">
                <div>
                    <h1>Sản phẩm</h1>
                    <p>Tìm kiếm, lọc danh mục, lọc giá và chọn sản phẩm phù hợp với bạn.</p>
                </div>

                <button class="btn-web-light product-filter-mobile-btn" type="button" id="productFilterToggle">
                    <i class="bi bi-funnel"></i>
                    Bộ lọc
                </button>
            </div>

            <div class="product-page-layout">
                <aside class="product-sidebar-filter" id="productSidebarFilter">
                    <div class="product-filter-head">
                        <strong>Bộ lọc sản phẩm</strong>

                        <button type="button" id="productFilterClose">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form action="{{ route('web.sanpham.index') }}" method="GET" class="product-filter-form">
                        <div class="filter-group">
                            <label>Tìm kiếm</label>
                            <input
                                type="text"
                                name="tu_khoa"
                                value="{{ $tukhoa }}"
                                placeholder="Tên sản phẩm, mã sản phẩm..."
                            >
                        </div>

                        <div class="filter-group">
                            <label>Danh mục</label>
                            <select name="danh_muc">
                                <option value="">Tất cả danh mục</option>
                                @foreach ($danhsachdanhmuc as $danhmuc)
                                    <option value="{{ $danhmuc->duong_dan }}" {{ $danhmucSlug === $danhmuc->duong_dan ? 'selected' : '' }}>
                                        {{ $danhmuc->ten_danh_muc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Khoảng giá</label>
                            <select name="khoang_gia">
                                <option value="">Tất cả mức giá</option>
                                <option value="duoi_200" {{ $khoangGia === 'duoi_200' ? 'selected' : '' }}>Dưới 200.000 ₫</option>
                                <option value="200_500" {{ $khoangGia === '200_500' ? 'selected' : '' }}>200.000 ₫ - 500.000 ₫</option>
                                <option value="500_1000" {{ $khoangGia === '500_1000' ? 'selected' : '' }}>500.000 ₫ - 1.000.000 ₫</option>
                                <option value="tren_1000" {{ $khoangGia === 'tren_1000' ? 'selected' : '' }}>Trên 1.000.000 ₫</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Sắp xếp</label>
                            <select name="sap_xep">
                                <option value="moi_nhat" {{ $sapxep === 'moi_nhat' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="gia_thap" {{ $sapxep === 'gia_thap' ? 'selected' : '' }}>Giá thấp đến cao</option>
                                <option value="gia_cao" {{ $sapxep === 'gia_cao' ? 'selected' : '' }}>Giá cao đến thấp</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Tình trạng</label>

                            <label class="filter-check">
                                <input type="checkbox" name="con_hang" value="1" {{ $chiConHang ? 'checked' : '' }}>
                                <span>Còn hàng</span>
                            </label>

                            <label class="filter-check">
                                <input type="checkbox" name="khuyen_mai" value="1" {{ $chiKhuyenMai ? 'checked' : '' }}>
                                <span>Đang khuyến mãi</span>
                            </label>

                            <label class="filter-check">
                                <input type="checkbox" name="noi_bat" value="1" {{ $chiNoiBat ? 'checked' : '' }}>
                                <span>Sản phẩm nổi bật</span>
                            </label>
                        </div>

                        <div class="filter-actions">
                            <button type="submit" class="btn-web-primary w-100">
                                <i class="bi bi-funnel"></i>
                                Áp dụng bộ lọc
                            </button>

                            @if ($tukhoa || $danhmucSlug || $sapxep !== 'moi_nhat' || $khoangGia || $chiConHang || $chiKhuyenMai || $chiNoiBat)
                                <a href="{{ route('web.sanpham.index') }}" class="btn-web-light w-100">
                                    Xóa bộ lọc
                                </a>
                            @endif
                        </div>
                    </form>
                </aside>

                <div class="product-page-content">
                    <div class="product-result-bar">
                        <div>
                            Tìm thấy <strong>{{ $danhsachsanpham->total() }}</strong> sản phẩm
                        </div>

                        <div class="product-result-tags">
                            @if ($tukhoa)
                                <span>Từ khóa: {{ $tukhoa }}</span>
                            @endif

                            @if ($danhmucSlug)
                                <span>Danh mục: {{ $danhmucSlug }}</span>
                            @endif

                            @if ($khoangGia)
                                <span>Khoảng giá đã chọn</span>
                            @endif
                        </div>
                    </div>

                    @if ($danhsachsanpham->count())
                        <div class="product-grid product-list-grid">
                            @foreach ($danhsachsanpham as $sanpham)
                                @include('web.components.the-san-pham', ['sanpham' => $sanpham])
                            @endforeach
                        </div>

                        @if ($danhsachsanpham->hasPages())
                            <div class="mt-4">
                                {{ $danhsachsanpham->links() }}
                            </div>
                        @endif
                    @else
                        <div class="empty-web product-empty-state">
                            <div class="empty-web-icon">
                                <i class="bi bi-search"></i>
                            </div>
                            <h5 class="fw-bold">Không tìm thấy sản phẩm phù hợp</h5>
                            <p class="text-muted mb-3">
                                Hãy thử từ khóa khác hoặc bỏ bớt bộ lọc.
                            </p>
                            <a href="{{ route('web.sanpham.index') }}" class="btn-web-primary">
                                Xem tất cả sản phẩm
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection
