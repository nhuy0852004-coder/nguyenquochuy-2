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

    <div class="content-card">
        <div class="content-card-header">
            <h2>Đơn hàng mới nhất</h2>
            <a href="#" class="btn-chinh">
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
                        <th>Số điện thoại</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donhangmoi as $donhang)
                        <tr>
                            <td class="fw-semibold">{{ $donhang['ma_don_hang'] }}</td>
                            <td>{{ $donhang['khach_hang'] }}</td>
                            <td>{{ $donhang['so_dien_thoai'] }}</td>
                            <td class="fw-semibold">{{ number_format($donhang['tong_tien'], 0, ',', '.') }} ₫</td>
                            <td>
                                @if ($donhang['trang_thai'] === 'Chờ xác nhận')
                                    <span class="badge-trang-thai badge-cho">Chờ xác nhận</span>
                                @elseif ($donhang['trang_thai'] === 'Đã xác nhận')
                                    <span class="badge-trang-thai badge-xacnhan">Đã xác nhận</span>
                                @else
                                    <span class="badge-trang-thai badge-giao">Đang giao hàng</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $donhang['thoi_gian'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
