@extends('admin.layouts.app')

@section('tieude', 'Chi tiết khách hàng')

@section('noidung')
    <div class="page-title">
        <h1>Chi tiết khách hàng</h1>
        <p>Xem thông tin khách hàng, thống kê mua hàng và lịch sử đơn hàng.</p>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('admin.khachhang.index') }}" class="btn-phu">
            <i class="bi bi-arrow-left"></i>
            Quay lại danh sách
        </a>
    </div>

    <div class="customer-stat-grid">
        <div class="customer-stat-card">
            <div class="customer-stat-label">Tổng số đơn</div>
            <div class="customer-stat-value">{{ $thongke['tong_so_don'] }}</div>
        </div>

        <div class="customer-stat-card">
            <div class="customer-stat-label">Đơn hợp lệ</div>
            <div class="customer-stat-value">{{ $thongke['tong_so_don_khong_huy'] }}</div>
        </div>

        <div class="customer-stat-card">
            <div class="customer-stat-label">Đơn đã hủy</div>
            <div class="customer-stat-value">{{ $thongke['tong_so_don_huy'] }}</div>
        </div>

        <div class="customer-stat-card">
            <div class="customer-stat-label">Tổng tiền đã mua</div>
            <div class="customer-stat-value">
                {{ number_format($thongke['tong_tien_da_mua'], 0, ',', '.') }} ₫
            </div>
        </div>
    </div>

    <div class="customer-detail-grid">
        <div>
            <div class="customer-info-card mb-3">
                <div class="customer-big-avatar">
                    {{ mb_substr($khachhang->ho_ten, 0, 1) }}
                </div>

                <h3>{{ $khachhang->ho_ten }}</h3>

                <div class="text-muted mb-3">
                    Mã khách hàng: KH{{ str_pad($khachhang->id, 5, '0', STR_PAD_LEFT) }}
                </div>

                <div class="customer-line">
                    <span>Số điện thoại</span>
                    <strong>{{ $khachhang->so_dien_thoai }}</strong>
                </div>

                <div class="customer-line">
                    <span>Email</span>
                    <strong>{{ $khachhang->email ?: 'Chưa có' }}</strong>
                </div>

                <div class="customer-line">
                    <span>Địa chỉ</span>
                    <strong>{{ $khachhang->dia_chi ?: 'Chưa có' }}</strong>
                </div>

                <div class="customer-line">
                    <span>Ngày tạo</span>
                    <strong>{{ $khachhang->created_at->format('d/m/Y H:i') }}</strong>
                </div>

                <div class="customer-line">
                    <span>Lần mua gần nhất</span>
                    <strong>
                        @if ($thongke['lan_mua_gan_nhat'])
                            {{ $thongke['lan_mua_gan_nhat']->created_at->format('d/m/Y H:i') }}
                        @else
                            Chưa có
                        @endif
                    </strong>
                </div>
            </div>
        </div>

        <div>
            <div class="content-card">
                <div class="content-card-header">
                    <h2>Lịch sử đơn hàng</h2>
                    <span class="text-muted small">
                        {{ $khachhang->donhang->count() }} đơn hàng
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Sản phẩm</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                                <th style="width: 90px;">Xem</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($khachhang->donhang as $donhang)
                                <tr>
                                    <td class="fw-bold">
                                        {{ $donhang->ma_don_hang }}
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $donhang->chitietdonhang->sum('so_luong') }} sản phẩm
                                        </div>
                                        <div class="text-muted small">
                                            {{ $donhang->chitietdonhang->take(2)->pluck('ten_san_pham')->join(', ') }}
                                            @if ($donhang->chitietdonhang->count() > 2)
                                                ...
                                            @endif
                                        </div>
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
                                            <p class="text-muted mb-0">
                                                Khách hàng này chưa có lịch sử mua hàng.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
