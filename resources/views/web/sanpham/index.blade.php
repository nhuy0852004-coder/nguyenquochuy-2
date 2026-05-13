@extends('web.layouts.app')

@section('tieude', 'Sản phẩm')

@section('mota', 'Danh sách sản phẩm thời trang, giá Việt Nam Đồng, tìm kiếm và lọc dễ dàng.')

@section('noidung')
    <div class="container">
        <div class="breadcrumb-web">
            <a href="{{ route('web.trangchu') }}">Trang chủ</a>
            <span class="mx-2">/</span>
            <span>Sản phẩm</span>
        </div>

        <section class="section-block">
            <div class="section-head">
                <div>
                    <h2>Sản phẩm</h2>
                    <p>Tìm kiếm, lọc danh mục và sắp xếp sản phẩm theo nhu cầu.</p>
                </div>
            </div>

            <div class="product-filter-box">
                <form action="{{ route('web.sanpham.index') }}" method="GET" class="product-filter-grid">
                    <input
                        type="text"
                        name="tu_khoa"
                        value="{{ $tukhoa }}"
                        placeholder="Tìm kiếm sản phẩm..."
                    >

                    <select name="danh_muc">
                        <option value="">Tất cả danh mục</option>
                        @foreach ($danhsachdanhmuc as $danhmuc)
                            <option value="{{ $danhmuc->duong_dan }}" {{ $danhmucSlug === $danhmuc->duong_dan ? 'selected' : '' }}>
                                {{ $danhmuc->ten_danh_muc }}
                            </option>
                        @endforeach
                    </select>

                    <select name="sap_xep">
                        <option value="moi_nhat" {{ $sapxep === 'moi_nhat' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="gia_thap" {{ $sapxep === 'gia_thap' ? 'selected' : '' }}>Giá thấp đến cao</option>
                        <option value="gia_cao" {{ $sapxep === 'gia_cao' ? 'selected' : '' }}>Giá cao đến thấp</option>
                    </select>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-web-primary">
                            <i class="bi bi-funnel"></i>
                            Lọc
                        </button>

                        @if ($tukhoa || $danhmucSlug || $sapxep !== 'moi_nhat')
                            <a href="{{ route('web.sanpham.index') }}" class="btn-web-light">
                                Xóa lọc
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="text-muted">
                    Tìm thấy <strong>{{ $danhsachsanpham->total() }}</strong> sản phẩm
                </div>
            </div>

            @if ($danhsachsanpham->count())
                <div class="product-grid">
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
                <div class="empty-web">
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
        </section>
    </div>
@endsection
