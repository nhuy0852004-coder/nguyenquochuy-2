<aside class="admin-sidebar">
    <div class="sidebar-logo">
        <i class="bi bi-shop me-2"></i>
        Bán Hàng Việt
    </div>

    <nav class="sidebar-menu">
        <div class="sidebar-label">Tổng quan</div>

        <a href="{{ route('admin.bangdieukhien') }}"
           class="sidebar-link {{ request()->routeIs('admin.bangdieukhien') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Bảng điều khiển</span>
        </a>

        <div class="sidebar-label">Quản lý bán hàng</div>

        <a href="{{ route('admin.danhmuc.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.danhmuc.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i>
            <span>Danh mục</span>
        </a>

        <a href="#" class="sidebar-link">
            <i class="bi bi-box-seam"></i>
            <span>Sản phẩm</span>
        </a>

        <a href="#" class="sidebar-link">
            <i class="bi bi-receipt"></i>
            <span>Đơn hàng</span>
        </a>

        <a href="#" class="sidebar-link">
            <i class="bi bi-people"></i>
            <span>Khách hàng</span>
        </a>

        <div class="sidebar-label">Vận hành</div>

        <a href="#" class="sidebar-link">
            <i class="bi bi-bell"></i>
            <span>Thông báo</span>
        </a>

        <a href="#" class="sidebar-link">
            <i class="bi bi-bar-chart"></i>
            <span>Báo cáo doanh thu</span>
        </a>

        <a href="#" class="sidebar-link">
            <i class="bi bi-gear"></i>
            <span>Cài đặt cửa hàng</span>
        </a>
    </nav>
</aside>
