@php
    $soLuongGioHang = collect(session('giohang', []))->sum('so_luong');
@endphp

<header class="web-header">
    <div class="web-topbar">
        <div class="container">
            <div class="web-topbar-inner">
                <div>
                    <i class="bi bi-truck me-1"></i>
                    Giao hàng toàn quốc - Thanh toán khi nhận hàng
                </div>

                <div class="web-topbar-contact">
                    @if (!empty($caidatcuahang?->so_dien_thoai))
                        <span><i class="bi bi-telephone me-1"></i>{{ $caidatcuahang->so_dien_thoai }}</span>
                    @endif

                    @if (!empty($caidatcuahang?->email))
                        <span><i class="bi bi-envelope me-1"></i>{{ $caidatcuahang->email }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="web-navbar">
            <button class="web-mobile-menu-btn" type="button" id="webMobileMenuBtn">
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
                <input type="text" name="tu_khoa" placeholder="Tìm sản phẩm..." value="{{ request('tu_khoa') }}">
                <button type="submit">
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
            <input type="text" name="tu_khoa" placeholder="Tìm sản phẩm bạn cần..." value="{{ request('tu_khoa') }}">
            <button type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <div class="web-mobile-menu" id="webMobileMenu">
        <div class="web-mobile-menu-head">
            <strong>Menu</strong>

            <button type="button" id="webMobileMenuClose">
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
            </a>

            <a href="{{ route('web.theodoi.index') }}">
                <i class="bi bi-receipt"></i>
                Theo dõi đơn hàng
            </a>
        </nav>
    </div>
</header>
