@extends('admin.layouts.app')

@section('tieude', 'Quản lý khách hàng')

@section('noidung')
    <div class="page-title">
        <h1>Quản lý khách hàng</h1>
        <p>Theo dõi khách hàng đã đặt hàng, lịch sử mua hàng và tổng giá trị đơn hàng.</p>
    </div>

    <div class="bo-loc">
        <form action="{{ route('admin.khachhang.index') }}" method="GET" class="customer-filter-grid">
            <div class="bo-loc-input">
                <i class="bi bi-search"></i>
                <input
                    type="text"
                    name="tu_khoa"
                    value="{{ $tukhoa }}"
                    placeholder="Tìm theo tên khách hàng, số điện thoại, email hoặc địa chỉ..."
                >
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-phu">
                    <i class="bi bi-search"></i>
                    Tìm kiếm
                </button>

                @if ($tukhoa)
                    <a href="{{ route('admin.khachhang.index') }}" class="btn-phu">
                        Xóa lọc
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h2>Danh sách khách hàng</h2>
            <span class="text-muted small">
                Tổng: {{ $danhsachkhachhang->total() }} khách hàng
            </span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Địa chỉ</th>
                        <th>Tổng đơn</th>
                        <th>Tổng tiền đã mua</th>
                        <th>Ngày tạo</th>
                        <th style="width: 100px;">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($danhsachkhachhang as $khachhang)
                        <tr>
                            <td>
                                <div class="customer-cell">
                                    <div class="customer-avatar">
                                        {{ mb_substr($khachhang->ho_ten, 0, 1) }}
                                    </div>

                                    <div>
                                        <div class="fw-bold">{{ $khachhang->ho_ten }}</div>
                                        <div class="text-muted small">
                                            Mã KH: KH{{ str_pad($khachhang->id, 5, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>{{ $khachhang->so_dien_thoai }}</td>

                            <td>
                                {{ $khachhang->email ?: 'Chưa có email' }}
                            </td>

                            <td>
                                <span class="text-muted">
                                    {{ $khachhang->dia_chi ?: 'Chưa có địa chỉ' }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-bold">{{ $khachhang->tong_so_don }}</div>
                                <div class="text-muted small">
                                    {{ $khachhang->tong_so_don_khong_huy }} đơn hợp lệ
                                </div>
                            </td>

                            <td class="fw-bold">
                                {{ number_format($khachhang->tong_tien_da_mua ?? 0, 0, ',', '.') }} ₫
                            </td>

                            <td class="text-muted">
                                {{ $khachhang->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                <a href="{{ route('admin.khachhang.chitiet', $khachhang) }}" class="btn-nho" title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <h5 class="fw-bold">Chưa có khách hàng</h5>
                                    <p class="text-muted mb-0">
                                        Khi khách đặt hàng từ website, thông tin khách hàng sẽ xuất hiện tại đây.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($danhsachkhachhang->hasPages())
            <div class="p-3 border-top">
                {{ $danhsachkhachhang->links() }}
            </div>
        @endif
    </div>
@endsection
