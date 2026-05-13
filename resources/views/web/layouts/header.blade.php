<header class="web-header">
    <div class="web-topbar py-2">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-truck me-1"></i>
                Giao hàng toàn quốc - Thanh toán khi nhận hàng
            </div>

            <div class="d-none d-md-flex gap-3">
                @if (!empty($caidatcuahang?->so_dien_thoai))
                    <span><i class="bi bi-telephone me-1"></i> {{ $caidatcuahang->so_dien_thoai }}</span>
                @endif

                @if (!empty($caidatcuahang?->email))
                    <span><i class="bi bi-envelope me-1"></i> {{ $caidatcuahang->email }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="container">
        <div class="web-navbar d-flex align-items-center justify-content-between">
            <a href="{{ route('web.trangchu') }}" class="web-logo">
                @if (!empty($caidatcuahang?->logo))
                    <img
                        src="{{ asset('storage/' . $caidatcuahang->logo) }}"
                        alt="{{ $caidatcuahang->ten_cua_hang }}"
                        style="height:34px; width:auto; margin-right:6px;"
                    >
                @else
                    <i class="bi bi-shop me-1"></i>
                @endif

                {{ $caidatcuahang->ten_cua_hang ?? 'Bán Hàng Việt' }}
            </a>

            <nav class="web-menu">
                <a href="{{ route('web.trangchu') }}" class="{{ request()->routeIs('web.trangchu') ? 'active' : '' }}">
                    Trang chủ
                </a>

                <a href="{{ route('web.sanpham.index') }}" class="{{ request()->routeIs('web.sanpham.*') ? 'active' : '' }}">
                    Sản phẩm
                </a>

                <a href="#">
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

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('web.theodoi.index') }}" class="web-icon-btn" title="Theo dõi đơn hàng">
                    <i class="bi bi-receipt"></i>
                </a>

                @php
    $soLuongGioHang = collect(session('giohang', []))->sum('so_luong');
@endphp

                <a href="{{ route('web.giohang.index') }}" class="web-icon-btn" id="nutGioHangHeader" title="Giỏ hàng">
                    <i class="bi bi-bag"></i>
                    <span class="web-badge-count" id="soLuongGioHang">{{ $soLuongGioHang }}</span>
                </a>
            </div>
        </div>
    </div>
</header>
