@extends('admin.layouts.app')

@section('tieude', 'Bảng điều khiển')

@section('noidung')
    @php
        $tongquan = $dulieu['tongquan'];
        $doanhthu14ngay = $dulieu['doanhthu14ngay'];
        $trangthaidonhang = $dulieu['trangthaidonhang'];
        $topsanpham = $dulieu['topsanpham'];
        $sanphamcanhbao = $dulieu['sanphamcanhbao'];
        $donhangmoi = $dulieu['donhangmoi'];

        $maxSoLuongTop = max(1, $topsanpham->max('tong_so_luong') ?? 1);
    @endphp

    <div class="page-title dashboard-title-row">
        <div>
            <h1>Bảng điều khiển</h1>
            <p>Phân tích nhanh tình hình doanh thu, đơn hàng, tồn kho và sản phẩm bán chạy.</p>
        </div>

        <div class="dashboard-title-actions">
            <a href="{{ route('admin.donhang.index') }}" class="btn-phu">
                <i class="bi bi-receipt"></i>
                Đơn hàng
            </a>

            @if (auth()->user()?->laAdmin())
                <a href="{{ route('admin.baocao.index') }}" class="btn-chinh">
                    <i class="bi bi-bar-chart"></i>
                    Xem báo cáo
                </a>
            @endif
        </div>
    </div>

    <div class="dashboard-metric-grid">
        <div class="dashboard-metric-card">
            <div class="metric-head">
                <span>Doanh thu hôm nay</span>
                <div class="metric-icon primary">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
            <div class="metric-value">{{ number_format($tongquan['doanh_thu_hom_nay'], 0, ',', '.') }} ₫</div>
            <div class="metric-sub">Không tính đơn đã hủy</div>
        </div>

        <div class="dashboard-metric-card">
            <div class="metric-head">
                <span>Doanh thu tuần này</span>
                <div class="metric-icon success">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
            <div class="metric-value">{{ number_format($tongquan['doanh_thu_tuan_nay'], 0, ',', '.') }} ₫</div>
            <div class="metric-sub">Tính từ đầu tuần đến hiện tại</div>
        </div>

        <div class="dashboard-metric-card">
            <div class="metric-head">
                <span>Doanh thu tháng này</span>
                <div class="metric-icon warning">
                    <i class="bi bi-calendar3"></i>
                </div>
            </div>
            <div class="metric-value">{{ number_format($tongquan['doanh_thu_thang_nay'], 0, ',', '.') }} ₫</div>
            <div class="metric-sub">Tổng doanh thu hợp lệ trong tháng</div>
        </div>

        <div class="dashboard-metric-card">
            <div class="metric-head">
                <span>Đơn hàng hôm nay</span>
                <div class="metric-icon info">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
            <div class="metric-value">{{ $tongquan['don_hang_hom_nay'] }}</div>
            <div class="metric-sub">Tổng đơn phát sinh hôm nay</div>
        </div>
    </div>

    <div class="quick-action-grid dashboard-status-grid">
        <a href="{{ route('admin.donhang.index', ['trang_thai' => 'cho_xac_nhan']) }}" class="quick-action-card">
            <div class="quick-action-icon warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <div class="quick-action-value">{{ $tongquan['don_cho_xac_nhan'] }}</div>
                <div class="quick-action-label">Đơn chờ xác nhận</div>
            </div>
        </a>

        <a href="{{ route('admin.donhang.index', ['trang_thai' => 'dang_giao_hang']) }}" class="quick-action-card">
            <div class="quick-action-icon primary">
                <i class="bi bi-truck"></i>
            </div>
            <div>
                <div class="quick-action-value">{{ $tongquan['don_dang_giao'] }}</div>
                <div class="quick-action-label">Đơn đang giao</div>
            </div>
        </a>

        <a href="{{ route('admin.sanpham.index') }}" class="quick-action-card">
            <div class="quick-action-icon danger">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div>
                <div class="quick-action-value">{{ $tongquan['san_pham_gan_het'] + $tongquan['san_pham_het_hang'] }}</div>
                <div class="quick-action-label">Sản phẩm cần chú ý</div>
            </div>
        </a>

        <a href="{{ route('admin.khachhang.index') }}" class="quick-action-card">
            <div class="quick-action-icon success">
                <i class="bi bi-people"></i>
            </div>
            <div>
                <div class="quick-action-value">{{ $tongquan['tong_khach_hang'] }}</div>
                <div class="quick-action-label">Tổng khách hàng</div>
            </div>
        </a>
    </div>

    <div class="dashboard-shortcuts">
        <a href="{{ route('admin.sanpham.index') }}" class="dashboard-shortcut">
            <i class="bi bi-box-seam"></i>
            Quản lý sản phẩm
        </a>

        <a href="{{ route('admin.donhang.index') }}" class="dashboard-shortcut">
            <i class="bi bi-receipt"></i>
            Xử lý đơn hàng
        </a>

        @if (auth()->user()?->laAdmin())
            <a href="{{ route('admin.baocao.index') }}" class="dashboard-shortcut">
                <i class="bi bi-bar-chart"></i>
                Xem báo cáo
            </a>

            <a href="{{ route('admin.caidatcuahang.index') }}" class="dashboard-shortcut">
                <i class="bi bi-gear"></i>
                Cài đặt cửa hàng
            </a>
        @endif
    </div>

    <div class="dashboard-chart-grid">
        <div class="dashboard-card">
            <div class="dashboard-card-head">
                <div>
                    <h2>Doanh thu 14 ngày gần nhất</h2>
                    <p>Theo đơn hàng hợp lệ, không tính đơn đã hủy.</p>
                </div>
            </div>

            <div class="dashboard-chart-box">
                <canvas id="bieuDoDoanhThu"></canvas>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="dashboard-card-head">
                <div>
                    <h2>Trạng thái đơn hàng</h2>
                    <p>Tỷ lệ đơn theo từng trạng thái.</p>
                </div>
            </div>

            <div class="dashboard-donut-box">
                <canvas id="bieuDoTrangThaiDon"></canvas>
            </div>

            <div class="dashboard-status-list">
                @foreach ($trangthaidonhang['labels'] as $index => $label)
                    <div class="status-line">
                        <span>{{ $label }}</span>
                        <strong>{{ $trangthaidonhang['values'][$index] }}</strong>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="dashboard-two-column">
        <div class="dashboard-card">
            <div class="dashboard-card-head">
                <div>
                    <h2>Top sản phẩm bán chạy</h2>
                    <p>Không tính các đơn đã hủy.</p>
                </div>

                <a href="{{ route('admin.sanpham.index') }}" class="btn-phu">
                    Xem sản phẩm
                </a>
            </div>

            @forelse ($topsanpham as $index => $sanpham)
                @php
                    $phanTram = ($sanpham->tong_so_luong / $maxSoLuongTop) * 100;
                @endphp

                <div class="top-product-item">
                    <div class="top-product-rank">{{ $index + 1 }}</div>

                    <div class="top-product-content">
                        <div class="top-product-name">{{ $sanpham->ten_san_pham }}</div>
                        <div class="top-product-meta">
                            {{ $sanpham->ma_san_pham ?: 'Chưa có mã' }} · Đã bán {{ $sanpham->tong_so_luong }}
                        </div>

                        <div class="top-product-progress">
                            <div style="width: {{ $phanTram }}%"></div>
                        </div>
                    </div>

                    <div class="top-product-money">
                        {{ number_format($sanpham->tong_doanh_thu, 0, ',', '.') }} ₫
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h5 class="fw-bold">Chưa có dữ liệu bán chạy</h5>
                    <p class="text-muted mb-0">Khi có đơn hàng, sản phẩm bán chạy sẽ hiển thị tại đây.</p>
                </div>
            @endforelse
        </div>

        <div class="dashboard-card">
            <div class="dashboard-card-head">
                <div>
                    <h2>Cảnh báo tồn kho</h2>
                    <p>Sản phẩm gần hết hàng hoặc đã hết hàng.</p>
                </div>

                <a href="{{ route('admin.sanpham.index') }}" class="btn-phu">
                    Xem kho
                </a>
            </div>

            @forelse ($sanphamcanhbao as $sanpham)
                <div class="stock-warning-item">
                    <div class="stock-warning-img">
                        @if ($sanpham->anh_dai_dien)
                            <img src="{{ asset('storage/' . $sanpham->anh_dai_dien) }}" alt="{{ $sanpham->ten_san_pham }}">
                        @else
                            <i class="bi bi-image"></i>
                        @endif
                    </div>

                    <div class="stock-warning-content">
                        <div class="stock-warning-name">{{ $sanpham->ten_san_pham }}</div>
                        <div class="stock-warning-meta">
                            {{ $sanpham->ma_san_pham ?: 'Chưa có mã' }}
                        </div>
                    </div>

                    <div>
                        @if ($sanpham->so_luong_ton <= 0)
                            <span class="badge-trang-thai badge-hethang">Hết hàng</span>
                        @else
                            <span class="badge-trang-thai badge-ganhet">Còn {{ $sanpham->so_luong_ton }}</span>
                        @endif
                    </div>
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

    <div class="dashboard-card">
        <div class="dashboard-card-head">
            <div>
                <h2>Đơn hàng mới nhất</h2>
                <p>Các đơn hàng vừa phát sinh gần đây.</p>
            </div>

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
                        <th style="width: 90px;">Xem</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($donhangmoi as $donhang)
                        <tr>
                            <td class="fw-bold">{{ $donhang->ma_don_hang }}</td>

                            <td>
                                <div>{{ $donhang->ho_ten_nguoi_nhan }}</div>
                                <div class="text-muted small">{{ $donhang->so_dien_thoai_nguoi_nhan }}</div>
                            </td>

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
                            <td colspan="6">
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
@endsection

@push('scripts')
    <script>
        const doanhThuLabels = @json($doanhthu14ngay['labels']);
        const doanhThuData = @json($doanhthu14ngay['doanh_thu']);
        const soDonData = @json($doanhthu14ngay['so_don']);

        const trangThaiLabels = @json($trangthaidonhang['labels']);
        const trangThaiValues = @json($trangthaidonhang['values']);

        function dinhDangTien(value) {
            return new Intl.NumberFormat('vi-VN').format(value) + ' ₫';
        }

        const doanhThuCanvas = document.getElementById('bieuDoDoanhThu');

        if (doanhThuCanvas) {
            new Chart(doanhThuCanvas, {
                type: 'line',
                data: {
                    labels: doanhThuLabels,
                    datasets: [
                        {
                            label: 'Doanh thu',
                            data: doanhThuData,
                            borderColor: '#2563EB',
                            backgroundColor: 'rgba(37, 99, 235, 0.08)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Số đơn',
                            data: soDonData,
                            borderColor: '#16A34A',
                            backgroundColor: 'rgba(22, 163, 74, 0.08)',
                            tension: 0.35,
                            fill: false,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if (context.dataset.label === 'Doanh thu') {
                                        return 'Doanh thu: ' + dinhDangTien(context.raw);
                                    }

                                    return 'Số đơn: ' + context.raw;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN').format(value);
                                }
                            },
                            grid: {
                                color: '#F3F4F6'
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        const trangThaiCanvas = document.getElementById('bieuDoTrangThaiDon');

        if (trangThaiCanvas) {
            new Chart(trangThaiCanvas, {
                type: 'doughnut',
                data: {
                    labels: trangThaiLabels,
                    datasets: [{
                        data: trangThaiValues,
                        backgroundColor: [
                            '#D97706',
                            '#2563EB',
                            '#0EA5E9',
                            '#16A34A',
                            '#DC2626'
                        ],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw + ' đơn';
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
