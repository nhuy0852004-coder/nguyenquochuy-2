@extends('admin.layouts.app')

@section('tieude', 'Báo cáo doanh thu')

@section('noidung')
    <div class="page-title">
        <h1>Báo cáo doanh thu</h1>
        <p>Theo dõi doanh thu, đơn hàng, sản phẩm bán chạy và hiệu quả kinh doanh.</p>
    </div>

    @if (session('loi'))
        <div class="alert alert-danger border-0 rounded-3">
            {{ session('loi') }}
        </div>
    @endif

    <div class="bo-loc">
        <form action="{{ route('admin.baocao.index') }}" method="GET" class="report-filter-grid">
            <div>
                <label class="form-label">Từ ngày</label>
                <input
                    type="date"
                    name="tu_ngay"
                    class="form-control"
                    value="{{ $tuNgay->format('Y-m-d') }}"
                >
            </div>

            <div>
                <label class="form-label">Đến ngày</label>
                <input
                    type="date"
                    name="den_ngay"
                    class="form-control"
                    value="{{ $denNgay->format('Y-m-d') }}"
                >
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-chinh">
                    <i class="bi bi-funnel"></i>
                    Lọc báo cáo
                </button>

                <a href="{{ route('admin.baocao.index') }}" class="btn-phu">
                    Tháng này
                </a>
            </div>
        </form>
    </div>

    <div class="report-stat-grid">
        <div class="report-stat-card">
            <div class="report-stat-label">Tổng doanh thu</div>
            <div class="report-stat-value text-primary">
                {{ number_format($thongke['tong_doanh_thu'], 0, ',', '.') }} ₫
            </div>
        </div>

        <div class="report-stat-card">
            <div class="report-stat-label">Tổng đơn hàng</div>
            <div class="report-stat-value">
                {{ $thongke['tong_don_hang'] }}
            </div>
        </div>

        <div class="report-stat-card">
            <div class="report-stat-label">Đơn hợp lệ</div>
            <div class="report-stat-value text-success">
                {{ $thongke['tong_don_hop_le'] }}
            </div>
        </div>

        <div class="report-stat-card">
            <div class="report-stat-label">Đơn đã hủy</div>
            <div class="report-stat-value text-danger">
                {{ $thongke['tong_don_huy'] }}
            </div>
        </div>

        <div class="report-stat-card">
            <div class="report-stat-label">Sản phẩm đã bán</div>
            <div class="report-stat-value">
                {{ $thongke['tong_san_pham_da_ban'] }}
            </div>
        </div>
    </div>

    <div class="report-quick-grid">
        <div class="report-stat-card">
            <div class="report-stat-label">Doanh thu hôm nay</div>
            <div class="report-stat-value">
                {{ number_format($doanhThuNhanh['hom_nay'], 0, ',', '.') }} ₫
            </div>
        </div>

        <div class="report-stat-card">
            <div class="report-stat-label">Doanh thu tuần này</div>
            <div class="report-stat-value">
                {{ number_format($doanhThuNhanh['tuan_nay'], 0, ',', '.') }} ₫
            </div>
        </div>

        <div class="report-stat-card">
            <div class="report-stat-label">Doanh thu tháng này</div>
            <div class="report-stat-value">
                {{ number_format($doanhThuNhanh['thang_nay'], 0, ',', '.') }} ₫
            </div>
        </div>
    </div>

    <div class="report-layout-grid">
        <div class="content-card">
            <div class="content-card-header">
                <h2>Doanh thu 7 ngày gần nhất</h2>
                <span class="text-muted small">Không tính đơn đã hủy</span>
            </div>

            <div class="p-3">
                @php
                    $maxDoanhThu = collect($doanhThu7Ngay)->max('doanh_thu') ?: 1;
                @endphp

                <div class="report-chart-list">
                    @foreach ($doanhThu7Ngay as $ngay)
                        @php
                            $phanTram = ($ngay['doanh_thu'] / $maxDoanhThu) * 100;
                        @endphp

                        <div class="report-chart-row">
                            <div>
                                <div class="fw-bold">{{ $ngay['ngay'] }}</div>
                                <div class="text-muted small">{{ $ngay['so_don'] }} đơn</div>
                            </div>

                            <div class="report-chart-bar-wrap">
                                <div class="report-chart-bar" style="width: {{ $phanTram }}%;"></div>
                            </div>

                            <div class="fw-bold text-end">
                                {{ number_format($ngay['doanh_thu'], 0, ',', '.') }} ₫
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="content-card">
            <div class="content-card-header">
                <h2>Top sản phẩm bán chạy</h2>
                <span class="text-muted small">Theo khoảng ngày lọc</span>
            </div>

            <div class="p-3">
                @forelse ($topSanPhamBanChay as $index => $sanpham)
                    <div class="top-product-row">
                        <div class="top-product-rank">
                            {{ $index + 1 }}
                        </div>

                        <div>
                            <div class="fw-bold">{{ $sanpham->ten_san_pham }}</div>
                            <div class="text-muted small">
                                {{ $sanpham->ma_san_pham ?: 'Chưa có mã' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-muted small">Đã bán</div>
                            <div class="fw-bold">{{ $sanpham->tong_so_luong }}</div>
                        </div>

                        <div class="text-end">
                            <div class="text-muted small">Doanh thu</div>
                            <div class="fw-bold">
                                {{ number_format($sanpham->tong_doanh_thu, 0, ',', '.') }} ₫
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h5 class="fw-bold">Chưa có sản phẩm bán chạy</h5>
                        <p class="text-muted mb-0">
                            Chưa có dữ liệu bán hàng trong khoảng thời gian này.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h2>Đơn hàng trong khoảng lọc</h2>
            <span class="text-muted small">
                Hiển thị 20 đơn mới nhất
            </span>
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
                        <th>Ngày đặt</th>
                        <th style="width: 90px;">Xem</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($danhsachdonhang as $donhang)
                        <tr>
                            <td class="fw-bold">
                                {{ $donhang->ma_don_hang }}
                            </td>

                            <td>{{ $donhang->ho_ten_nguoi_nhan }}</td>

                            <td>{{ $donhang->so_dien_thoai_nguoi_nhan }}</td>

                            <td class="fw-bold">
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

                            <td>
                                <a href="{{ route('admin.donhang.chitiet', $donhang) }}" class="btn-nho">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-receipt"></i>
                                    </div>
                                    <h5 class="fw-bold">Chưa có đơn hàng</h5>
                                    <p class="text-muted mb-0">
                                        Không có đơn hàng nào trong khoảng thời gian này.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
