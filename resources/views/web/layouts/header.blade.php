@php
    $soLuongGioHang = collect(session('giohang', []))->sum('so_luong');
@endphp

<header class="web-header">
    <div class="web-topbar">
        <div class="container">
            <div class="web-topbar-inner">
                <div class="web-topbar-left">
                    <span>
                        <i class="bi bi-truck me-1"></i>
                        Giao hàng toàn quốc
                    </span>

                    <span class="d-none d-lg-inline">
                        <i class="bi bi-arrow-repeat me-1"></i>
                        Đổi trả trong 7 ngày
                    </span>

                    <span class="d-none d-xl-inline">
                        <i class="bi bi-cash-coin me-1"></i>
                        Thanh toán khi nhận hàng
                    </span>
                </div>

                <div class="web-topbar-contact">
                    @if (!empty($caidatcuahang?->so_dien_thoai))
                        <span>
                            <i class="bi bi-telephone me-1"></i>
                            {{ $caidatcuahang->so_dien_thoai }}
                        </span>
                    @endif

                    @if (!empty($caidatcuahang?->email))
                        <span class="d-none d-md-inline">
                            <i class="bi bi-envelope me-1"></i>
                            {{ $caidatcuahang->email }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="web-navbar">
            <button class="web-mobile-menu-btn" type="button" id="webMobileMenuBtn" aria-label="Mở menu">
                <i class="bi bi-list"></i>
            </button>

            <a href="{{ route('web.trangchu') }}" class="web-logo">
                @if (!empty($caidatcuahang?->logo))
                    <img
                        src="{{ asset('storage/' . $caidatcuahang->logo) }}"
                        alt="{{ $caidatcuahang->ten_cua_hang }}"
                    >
                @else
                    <span class="web-logo-icon">
                        <i class="bi bi-shop"></i>
                    </span>
                @endif

                <span>{{ $caidatcuahang->ten_cua_hang ?? 'Bán Hàng Việt' }}</span>
            </a>

            <nav class="web-menu">
                <a href="{{ route('web.trangchu') }}" class="{{ request()->routeIs('web.trangchu') ? 'active' : '' }}">
                    Trang chủ
                </a>

                <a href="{{ route('web.sanpham.index') }}" class="{{ request()->routeIs('web.sanpham.*') ? 'active' : '' }}">
                    Sản phẩm
                </a>

                <a href="{{ route('web.sanpham.index', ['sap_xep' => 'gia_thap']) }}">
                    Khuyến mãi
                </a>

                <a href="{{ route('web.theodoi.index') }}" class="{{ request()->routeIs('web.theodoi.*') ? 'active' : '' }}">
                    Theo dõi đơn
                </a>
            </nav>

            <form action="{{ route('web.sanpham.index') }}" method="GET" class="web-search">
                <input
                    type="text"
                    name="tu_khoa"
                    placeholder="Tìm áo, quần, giày, túi xách..."
                    value="{{ request('tu_khoa') }}"
                >
                <button type="submit" aria-label="Tìm kiếm">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <div class="web-actions">
                <button class="web-icon-btn d-lg-none" type="button" id="webSearchMobileBtn" title="Tìm kiếm">
                    <i class="bi bi-search"></i>
                </button>

                <a href="{{ route('web.theodoi.index') }}" class="web-icon-btn" title="Theo dõi đơn hàng">
                    <i class="bi bi-receipt"></i>
                </a>

                <a href="{{ route('web.giohang.index') }}" class="web-icon-btn" id="nutGioHangHeader" title="Giỏ hàng">
                    <i class="bi bi-bag"></i>
                    <span class="web-badge-count" id="soLuongGioHang">{{ $soLuongGioHang }}</span>
                </a>
            </div>
        </div>

        <form action="{{ route('web.sanpham.index') }}" method="GET" class="web-mobile-search" id="webMobileSearch">
            <input
                type="text"
                name="tu_khoa"
                placeholder="Tìm sản phẩm bạn cần..."
                value="{{ request('tu_khoa') }}"
            >
            <button type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <div class="web-mobile-menu" id="webMobileMenu">
        <div class="web-mobile-menu-head">
            <div>
                <strong>Menu</strong>
                <div class="text-muted small">Điều hướng nhanh</div>
            </div>

            <button type="button" id="webMobileMenuClose" aria-label="Đóng menu">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <nav>
            <a href="{{ route('web.trangchu') }}">
                <i class="bi bi-house"></i>
                Trang chủ
            </a>

            <a href="{{ route('web.sanpham.index') }}">
                <i class="bi bi-bag"></i>
                Sản phẩm
            </a>

            <a href="{{ route('web.sanpham.index', ['sap_xep' => 'gia_thap']) }}">
                <i class="bi bi-percent"></i>
                Khuyến mãi
            </a>

            <a href="{{ route('web.giohang.index') }}">
                <i class="bi bi-cart"></i>
                Giỏ hàng
                @if ($soLuongGioHang > 0)
                    <span class="mobile-menu-count">{{ $soLuongGioHang }}</span>
                @endif
            </a>

            <a href="{{ route('web.theodoi.index') }}">
                <i class="bi bi-receipt"></i>
                Theo dõi đơn hàng
            </a>
        </nav>

        <div class="web-mobile-menu-contact">
            @if (!empty($caidatcuahang?->so_dien_thoai))
                <div>
                    <i class="bi bi-telephone"></i>
                    {{ $caidatcuahang->so_dien_thoai }}
                </div>
            @endif

            @if (!empty($caidatcuahang?->dia_chi))
                <div>
                    <i class="bi bi-geo-alt"></i>
                    {{ $caidatcuahang->dia_chi }}
                </div>
            @endif
        </div>
    </div>
</header>
