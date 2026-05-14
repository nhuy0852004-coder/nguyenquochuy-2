@php
    $admin = auth()->user();
@endphp

<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-logo">
        <a href="{{ route('admin.bangdieukhien') }}" class="sidebar-logo-link">
            <span class="sidebar-logo-icon">
                <i class="bi bi-shop"></i>
            </span>

            <span class="sidebar-logo-text">
                Bán Hàng Việt
            </span>
        </a>

        <button class="sidebar-close d-lg-none" type="button" id="sidebarClose">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="sidebar-scroll">
        <nav class="sidebar-menu">
            <div class="sidebar-label">Tổng quan</div>

            <a href="{{ route('admin.bangdieukhien') }}"
               class="sidebar-link {{ request()->routeIs('admin.bangdieukhien') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Bảng điều khiển</span>
            </a>

            <div class="sidebar-label">Quản lý bán hàng</div>

            @if ($admin?->laAdmin())
                <a href="{{ route('admin.danhmuc.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.danhmuc.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i>
                    <span>Danh mục</span>
                </a>
            @endif

            <a href="{{ route('admin.sanpham.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.sanpham.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>Sản phẩm</span>
            </a>

            <a href="{{ route('admin.donhang.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.donhang.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>
                <span>Đơn hàng</span>
            </a>

            <a href="{{ route('admin.khachhang.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.khachhang.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Khách hàng</span>
            </a>

            <div class="sidebar-label">Vận hành</div>

            @if ($admin?->laAdmin())
                <a href="{{ route('admin.nguoidung.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.nguoidung.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i>
                    <span>Người dùng</span>
                </a>
            @endif

            <a href="{{ route('admin.thongbao.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.thongbao.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i>
                <span>Thông báo</span>
            </a>

            @if ($admin?->laAdmin())
                <a href="{{ route('admin.baocao.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.baocao.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart"></i>
                    <span>Báo cáo doanh thu</span>
                </a>
            @endif

            @if ($admin?->laAdmin())
                <a href="{{ route('admin.caidatcuahang.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.caidatcuahang.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>Cài đặt cửa hàng</span>
                </a>
            @endif
        </nav>
    </div>

    <div class="sidebar-footer">
        <div class="sidebar-footer-title">Phiên bản local</div>
        <div class="sidebar-footer-text">Laravel + XAMPP + MySQL</div>
    </div>
</aside>
