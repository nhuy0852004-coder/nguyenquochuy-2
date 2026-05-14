@php
    $thongbaoRepository = app(\App\Repositories\ThongbaoRepository::class);

    $soluongThongBaoChuaDoc = cache()->remember('thongbao_chua_doc_count', 30, function () use ($thongbaoRepository) {
        return $thongbaoRepository->demChuaDoc();
    });

    $thongBaoMoiNhat = cache()->remember('thongbao_moi_nhat', 30, function () use ($thongbaoRepository) {
        return $thongbaoRepository->layMoiNhat(5);
    });

    $adminDangNhap = auth()->user();
@endphp

<header class="admin-header">
    <div class="header-left">
        <button class="header-menu-btn" type="button" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>

        <form action="{{ route('admin.donhang.index') }}" method="GET" class="header-search">
            <i class="bi bi-search"></i>
            <input
                type="text"
                name="tu_khoa"
                placeholder="Tìm mã đơn, khách hàng, số điện thoại..."
                value="{{ request('tu_khoa') }}"
            >
        </form>
    </div>

    <div class="header-actions">
        <a href="{{ route('web.trangchu') }}" target="_blank" class="header-visit-store">
            <i class="bi bi-box-arrow-up-right"></i>
            <span>Xem website</span>
        </a>

        <div class="dropdown">
            <button class="header-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell"></i>

                <span class="dot" id="adminDotThongBao" style="{{ $soluongThongBaoChuaDoc > 0 ? '' : 'display:none;' }}"></span>

                <span
                    class="header-count"
                    id="adminSoThongBao"
                    style="{{ $soluongThongBaoChuaDoc > 0 ? '' : 'display:none;' }}"
                >
                    {{ $soluongThongBaoChuaDoc > 99 ? '99+' : $soluongThongBaoChuaDoc }}
                </span>
            </button>

            <div class="dropdown-menu dropdown-menu-end thongbao-dropdown" id="adminThongBaoDropdown">
                <div class="thongbao-dropdown-head">
                    <div>
                        <div class="fw-bold">Thông báo</div>
                        <div class="text-muted small">
                            <span id="adminTextThongBaoChuaDoc">{{ $soluongThongBaoChuaDoc }}</span> chưa đọc
                        </div>
                    </div>

                    <a href="{{ route('admin.thongbao.index') }}" class="small text-decoration-none">
                        Xem tất cả
                    </a>
                </div>

                <div id="adminThongBaoList">
                    @forelse ($thongBaoMoiNhat as $thongbao)
                        <form action="{{ route('admin.thongbao.dadoc', $thongbao) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <button type="submit" class="thongbao-dropdown-item {{ $thongbao->da_doc ? '' : 'chua-doc' }}">
                                <span class="thongbao-icon {{ $thongbao->loaiClass() }}">
                                    @if ($thongbao->loai === App\Models\Thongbao::LOAI_DON_HANG)
                                        <i class="bi bi-receipt"></i>
                                    @elseif ($thongbao->loai === App\Models\Thongbao::LOAI_TON_KHO)
                                        <i class="bi bi-box-seam"></i>
                                    @else
                                        <i class="bi bi-info-circle"></i>
                                    @endif
                                </span>

                                <span class="thongbao-dropdown-content">
                                    <strong>{{ $thongbao->tieu_de }}</strong>
                                    <small>{{ $thongbao->created_at->diffForHumans() }}</small>
                                </span>
                            </button>
                        </form>
                    @empty
                        <div class="p-3 text-center text-muted" id="adminThongBaoRong">
                            Chưa có thông báo
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="header-user dropdown">
            <button class="header-user-btn" type="button" data-bs-toggle="dropdown">
                <div class="header-avatar">
                    {{ mb_substr($adminDangNhap?->ho_ten ?? 'A', 0, 1) }}
                </div>

                <div class="d-none d-md-block text-start">
                    <div class="fw-semibold">{{ $adminDangNhap?->ho_ten ?? 'Quản trị viên' }}</div>
                    <div class="text-muted small">
                        {{ $adminDangNhap?->email ?? 'admin@cuahang.vn' }}
                        · {{ $adminDangNhap?->tenVaiTro() ?? 'Quản trị viên' }}
                    </div>
                </div>

                <i class="bi bi-chevron-down small text-muted d-none d-md-inline"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-end">
                <a href="{{ route('admin.caidatcuahang.index') }}" class="dropdown-item">
                    <i class="bi bi-gear me-2"></i>
                    Cài đặt cửa hàng
                </a>

                <a href="{{ route('web.trangchu') }}" target="_blank" class="dropdown-item">
                    <i class="bi bi-box-arrow-up-right me-2"></i>
                    Xem website
                </a>

                <div class="dropdown-divider"></div>

                <form
                    action="{{ route('admin.dangxuat') }}"
                    method="POST"
                    data-confirm-title="Đăng xuất?"
                    data-confirm="Bạn có chắc muốn đăng xuất khỏi hệ thống quản trị không?"
                    data-confirm-button="Đăng xuất"
                    data-confirm-icon="question"
                >
                    @csrf

                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
