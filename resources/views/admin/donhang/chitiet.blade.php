@extends('admin.layouts.app')

@section('tieude', 'Chi tiết đơn hàng')

@section('noidung')
    <div class="page-title">
        <h1>Chi tiết đơn hàng {{ $donhang->ma_don_hang }}</h1>
        <p>Xem thông tin người nhận, sản phẩm trong đơn và cập nhật trạng thái xử lý.</p>
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

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-3">
            <div class="fw-bold mb-1">Vui lòng kiểm tra lại thông tin</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $loi)
                    <li>{{ $loi }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('admin.donhang.index') }}" class="btn-phu">
            <i class="bi bi-arrow-left"></i>
            Quay lại danh sách
        </a>

        <span class="badge-trang-thai {{ $donhang->trangThaiDonHangClass() }}">
            {{ $donhang->trangThaiDonHangText() }}
        </span>
    </div>

    <div class="order-detail-grid">
        <div>
            <div class="content-card">
                <div class="content-card-header">
                    <h2>Sản phẩm trong đơn</h2>
                    <span class="text-muted small">
                        {{ $donhang->chitietdonhang->count() }} sản phẩm
                    </span>
                </div>

                @foreach ($donhang->chitietdonhang as $chitiet)
                    <div class="order-product">
                        <div class="order-product-img">
                            @if ($chitiet->anh_san_pham)
                                <img src="{{ asset('storage/' . $chitiet->anh_san_pham) }}" alt="{{ $chitiet->ten_san_pham }}">
                            @else
                                <i class="bi bi-image"></i>
                            @endif
                        </div>

                        <div>
                            <div class="fw-bold">{{ $chitiet->ten_san_pham }}</div>
                            <div class="text-muted small">
                                Mã: {{ $chitiet->ma_san_pham ?: 'Đang cập nhật' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-muted small">Đơn giá</div>
                            <div class="fw-bold">{{ number_format($chitiet->don_gia, 0, ',', '.') }} ₫</div>
                        </div>

                        <div>
                            <div class="text-muted small">Số lượng</div>
                            <div class="fw-bold">{{ $chitiet->so_luong }}</div>
                        </div>

                        <div>
                            <div class="text-muted small">Thành tiền</div>
                            <div class="fw-bold">{{ number_format($chitiet->thanh_tien, 0, ',', '.') }} ₫</div>
                        </div>
                    </div>
                @endforeach

                <div class="order-total-box">
                    <div class="order-line">
                        <span>Tạm tính</span>
                        <strong>{{ number_format($donhang->tam_tinh, 0, ',', '.') }} ₫</strong>
                    </div>

                    <div class="order-line">
                        <span>Phí vận chuyển</span>
                        <strong>
                            @if ($donhang->phi_van_chuyen > 0)
                                {{ number_format($donhang->phi_van_chuyen, 0, ',', '.') }} ₫
                            @else
                                Miễn phí
                            @endif
                        </strong>
                    </div>

                    <div class="order-line">
                        <span>Tổng tiền</span>
                        <strong class="text-danger fs-5">
                            {{ number_format($donhang->tong_tien, 0, ',', '.') }} ₫
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="order-info-box mb-3">
                <h3>Cập nhật trạng thái</h3>

                <form action="{{ route('admin.donhang.capnhattrangthai', $donhang) }}" method="POST" class="status-form">
                    @csrf
                    @method('PATCH')

                    <select name="trang_thai_don_hang" class="form-select">
                        @foreach ($danhsachtrangthai as $key => $label)
                            <option value="{{ $key }}" {{ $donhang->trang_thai_don_hang === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn-chinh">
                        <i class="bi bi-save"></i>
                        Lưu
                    </button>
                </form>
            </div>

            <div class="order-info-box mb-3">
                <h3>Thông tin người nhận</h3>

                <div class="order-line">
                    <span>Họ tên</span>
                    <strong>{{ $donhang->ho_ten_nguoi_nhan }}</strong>
                </div>

                <div class="order-line">
                    <span>Số điện thoại</span>
                    <strong>{{ $donhang->so_dien_thoai_nguoi_nhan }}</strong>
                </div>

                <div class="order-line">
                    <span>Email</span>
                    <strong>{{ $donhang->email_nguoi_nhan ?: 'Chưa có' }}</strong>
                </div>

                <div class="order-line">
                    <span>Địa chỉ</span>
                    <strong>{{ $donhang->dia_chi_giao_hang }}</strong>
                </div>

                <div class="order-line">
                    <span>Ghi chú</span>
                    <strong>{{ $donhang->ghi_chu ?: 'Không có' }}</strong>
                </div>
            </div>

            <div class="order-info-box mb-3">
                <h3>Thông tin đơn hàng</h3>

                <div class="order-line">
                    <span>Mã đơn</span>
                    <strong>{{ $donhang->ma_don_hang }}</strong>
                </div>

                <div class="order-line">
                    <span>Ngày đặt</span>
                    <strong>{{ $donhang->created_at->format('d/m/Y H:i') }}</strong>
                </div>

                <div class="order-line">
                    <span>Thanh toán</span>
                    <strong>{{ $donhang->phuongThucThanhToanText() }}</strong>
                </div>

                <div class="order-line">
                    <span>Trạng thái thanh toán</span>
                    <strong>{{ $donhang->trangThaiThanhToanText() }}</strong>
                </div>
            </div>
        </div>
    </div>
@endsection

