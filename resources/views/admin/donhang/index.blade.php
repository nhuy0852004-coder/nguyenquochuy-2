@extends('admin.layouts.app')

@section('tieude', 'Quản lý đơn hàng')

@section('noidung')
    <div class="page-title">
        <h1>Quản lý đơn hàng</h1>
        <p>Theo dõi đơn hàng khách đặt từ website và xử lý trạng thái giao hàng.</p>
    </div>

    <div class="order-tabs">
        <a href="{{ route('admin.donhang.index') }}"
           class="order-tab {{ !$trangthai ? 'active' : '' }}">
            Tất cả
        </a>

        @foreach ($danhsachtrangthai as $key => $label)
            <a href="{{ route('admin.donhang.index', ['trang_thai' => $key]) }}"
               class="order-tab {{ $trangthai === $key ? 'active' : '' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if (session('thanhcong'))
        <div class="toast-thongbao" id="toastThongBao">
            <i class="bi bi-check-circle-fill"></i>
            <div>
                <div class="fw-bold">Thành công</div>
                <div class="text-muted">{{ session('thanhcong') }}</div>
            </div>
        </div>
    @endif

    @if (session('loi'))
        <div class="alert alert-danger border-0 rounded-3">
            {{ session('loi') }}
        </div>
    @endif

    <div class="bo-loc">
        <form action="{{ route('admin.donhang.index') }}" method="GET" class="order-filter-grid">
            <div class="bo-loc-input">
                <i class="bi bi-search"></i>
                <input
                    type="text"
                    name="tu_khoa"
                    value="{{ $tukhoa }}"
                    placeholder="Tìm theo mã đơn, tên khách, số điện thoại, email..."
                >
            </div>

            <select name="trang_thai" class="form-select">
                <option value="">Tất cả trạng thái</option>
                @foreach ($danhsachtrangthai as $key => $label)
                    <option value="{{ $key }}" {{ $trangthai === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-phu">
                    <i class="bi bi-funnel"></i>
                    Lọc
                </button>

                @if ($tukhoa || $trangthai)
                    <a href="{{ route('admin.donhang.index') }}" class="btn-phu">
                        Xóa lọc
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h2>Danh sách đơn hàng</h2>
            <span class="text-muted small">
                Tổng: {{ $danhsachdonhang->total() }} đơn hàng
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
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th style="width: 110px;">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($danhsachdonhang as $donhang)
                        <tr>
                            <td class="fw-bold">
                                {{ $donhang->ma_don_hang }}
                            </td>

                            <td>
                                <div class="fw-semibold">{{ $donhang->ho_ten_nguoi_nhan }}</div>
                                <div class="text-muted small">
                                    {{ $donhang->email_nguoi_nhan ?: 'Chưa có email' }}
                                </div>
                            </td>

                            <td>{{ $donhang->so_dien_thoai_nguoi_nhan }}</td>

                            <td class="fw-bold">
                                {{ number_format($donhang->tong_tien, 0, ',', '.') }} ₫
                            </td>

                            <td>
                                <div>{{ $donhang->phuongThucThanhToanText() }}</div>
                                <div class="text-muted small">
                                    {{ $donhang->trangThaiThanhToanText() }}
                                </div>
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
                                <div class="table-actions">
                                    <a href="{{ route('admin.donhang.chitiet', $donhang) }}" class="btn-nho" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if ($donhang->trang_thai_don_hang === \App\Models\Donhang::TRANG_THAI_CHO_XAC_NHAN)
                                        <form action="{{ route('admin.donhang.capnhattrangthai', $donhang) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="trang_thai_don_hang" value="da_xac_nhan">

                                            <button type="submit" class="btn-nho" title="Xác nhận đơn">
                                                <i class="bi bi-check2"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-receipt"></i>
                                    </div>
                                    <h5 class="fw-bold">Chưa có đơn hàng nào</h5>
                                    <p class="text-muted mb-0">
                                        Khi khách đặt hàng từ website, đơn hàng sẽ xuất hiện tại đây.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($danhsachdonhang->hasPages())
            <div class="p-3 border-top">
                {{ $danhsachdonhang->links() }}
            </div>
        @endif
    </div>
@endsection

