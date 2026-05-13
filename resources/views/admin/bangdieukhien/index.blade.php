@extends('admin.layouts.app')

@section('tieude', 'Bảng điều khiển')

@section('noidung')
    <div class="page-title">
        <h1>Bảng điều khiển</h1>
        <p>Theo dõi nhanh tình hình bán hàng, đơn hàng và sản phẩm trong cửa hàng.</p>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-label">Doanh thu hôm nay</div>
                <div class="stat-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
            <div class="stat-value">{{ number_format($thongke['doanh_thu_hom_nay'], 0, ',', '.') }} ₫</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-label">Đơn hàng hôm nay</div>
                <div class="stat-icon">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
            <div class="stat-value">{{ $thongke['don_hang_hom_nay'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-label">Tổng sản phẩm</div>
                <div class="stat-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            <div class="stat-value">{{ $thongke['tong_san_pham'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-label">Tổng khách hàng</div>
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="stat-value">{{ $thongke['tong_khach_hang'] }}</div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="content-card">
                <div class="content-card-header">
                    <h2>Đơn hàng mới nhất</h2>
                    <a href="{{ route('admin.donhang.index') }}" class="btn-chinh">
                        <i class="bi bi-eye"></i>
                        Xem tất cả
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($donhangmoi as $donhang)
                                <tr>
                                    <td class="fw-semibold">
                                        <a href="{{ route('admin.donhang.chitiet', $donhang) }}" class="text-decoration-none">
                                            {{ $donhang->ma_don_hang }}
                                        </a>
                                    </td>
                                    <td>
                                        <div>{{ $donhang->ho_ten_nguoi_nhan }}</div>
                                        <div class="text-muted small">{{ $donhang->so_dien_thoai_nguoi_nhan }}</div>
                                    </td>
                                    <td class="fw-semibold">
                                        {{ number_format($donhang->tong_tien, 0, ',', '.') }} ₫
                                    </td>
                                    <td>
                                        <span class="badge-trang-thai {{ $donhang->trangThaiDonHangClass() }}">
                                            {{ $donhang->trangThaiDonHangText() }}
                                        </span>
                                    </td>
                                    <td class="text-muted">
                                        {{ $donhang->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="bi bi-receipt"></i>
                                            </div>
                                            <h5 class="fw-bold">Chưa có đơn hàng</h5>
                                            <p class="text-muted mb-0">Khi khách đặt hàng, đơn mới sẽ hiển thị tại đây.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="content-card">
                <div class="content-card-header">
                    <h2>Sản phẩm gần hết hàng</h2>
                    <a href="{{ route('admin.sanpham.index') }}" class="btn-phu">
                        Xem
                    </a>
                </div>

                <div class="p-3">
                    @forelse ($sanphamganhet as $sanpham)
                        <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                            <div>
                                <div class="fw-semibold">{{ $sanpham->ten_san_pham }}</div>
                                <div class="text-muted small">
                                    Cảnh báo khi còn {{ $sanpham->muc_canh_bao_ton }}
                                </div>
                            </div>

                            @if ($sanpham->so_luong_ton <= 0)
                                <span class="badge-trang-thai badge-hethang">Hết hàng</span>
                            @else
                                <span class="badge-trang-thai badge-ganhet">Còn {{ $sanpham->so_luong_ton }}</span>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <h5 class="fw-bold">Tồn kho ổn định</h5>
                            <p class="text-muted mb-0">Chưa có sản phẩm nào gần hết hàng.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
