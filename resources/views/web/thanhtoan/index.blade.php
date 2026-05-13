@extends('web.layouts.app')

@section('tieude', 'Thanh toán')

@section('noidung')
    <div class="container">
        <div class="breadcrumb-web">
            <a href="{{ route('web.trangchu') }}">Trang chủ</a>
            <span class="mx-2">/</span>
            <a href="{{ route('web.giohang.index') }}">Giỏ hàng</a>
            <span class="mx-2">/</span>
            <span>Thanh toán</span>
        </div>

        <section class="section-block">
            <div class="section-head">
                <div>
                    <h2>Thanh toán</h2>
                    <p>Nhập thông tin giao hàng và xác nhận đơn hàng.</p>
                </div>
            </div>

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

            <form action="{{ route('web.thanhtoan.dathang') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="checkout-box">
                            <h5 class="fw-bold mb-3">Thông tin người nhận</h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="ho_ten"
                                        class="form-control"
                                        value="{{ old('ho_ten') }}"
                                        placeholder="Ví dụ: Nguyễn Văn An"
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="so_dien_thoai"
                                        class="form-control"
                                        value="{{ old('so_dien_thoai') }}"
                                        placeholder="Ví dụ: 0901234567"
                                        required
                                    >
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Email</label>
                                    <input
                                        type="email"
                                        name="email"
                                        class="form-control"
                                        value="{{ old('email') }}"
                                        placeholder="Ví dụ: nguyenvanan@gmail.com"
                                    >
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="dia_chi"
                                        class="form-control"
                                        value="{{ old('dia_chi') }}"
                                        placeholder="Ví dụ: Quận Ninh Kiều, Cần Thơ"
                                        required
                                    >
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Ghi chú</label>
                                    <textarea
                                        name="ghi_chu"
                                        rows="4"
                                        class="form-control"
                                        placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao..."
                                    >{{ old('ghi_chu') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="checkout-box mt-3">
                            <h5 class="fw-bold mb-3">Phương thức thanh toán</h5>

                            <label class="payment-option">
                                <input
                                    type="radio"
                                    name="phuong_thuc_thanh_toan"
                                    value="cod"
                                    {{ old('phuong_thuc_thanh_toan', 'cod') === 'cod' ? 'checked' : '' }}
                                >
                                <div>
                                    <div class="fw-bold">Thanh toán khi nhận hàng</div>
                                    <div class="text-muted small">Khách thanh toán tiền mặt cho nhân viên giao hàng.</div>
                                </div>
                            </label>

                            <label class="payment-option">
                                <input
                                    type="radio"
                                    name="phuong_thuc_thanh_toan"
                                    value="chuyen_khoan"
                                    {{ old('phuong_thuc_thanh_toan') === 'chuyen_khoan' ? 'checked' : '' }}
                                >
                                <div>
                                    <div class="fw-bold">Chuyển khoản ngân hàng</div>
                                    <div class="text-muted small">
                                        Admin sẽ xác nhận thanh toán sau khi nhận được chuyển khoản.
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="cart-summary">
                            <h5 class="fw-bold mb-3">Đơn hàng của bạn</h5>

                            <div class="checkout-products">
                                @foreach ($giohang as $item)
                                    <div class="checkout-product">
                                        <div class="checkout-product-img">
                                            @if ($item['anh_dai_dien'])
                                                <img src="{{ asset('storage/' . $item['anh_dai_dien']) }}" alt="{{ $item['ten_san_pham'] }}">
                                            @else
                                                <i class="bi bi-image"></i>
                                            @endif
                                        </div>

                                        <div class="flex-fill">
                                            <div class="fw-bold">{{ $item['ten_san_pham'] }}</div>
                                            <div class="text-muted small">
                                                {{ number_format($item['don_gia'], 0, ',', '.') }} ₫ x {{ $item['so_luong'] }}
                                            </div>
                                        </div>

                                        <div class="fw-bold">
                                            {{ number_format($item['thanh_tien'], 0, ',', '.') }} ₫
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="summary-line mt-3">
                                <span>Tạm tính</span>
                                <strong>{{ number_format($tamTinh, 0, ',', '.') }} ₫</strong>
                            </div>

                            <div class="summary-line">
                                <span>Phí vận chuyển</span>
                                @if ($phiVanChuyen > 0)
                                    <strong>{{ number_format($phiVanChuyen, 0, ',', '.') }} ₫</strong>
                                @else
                                    <strong class="text-success">Miễn phí</strong>
                                @endif
                            </div>

                            <div class="summary-total">
                                <span>Tổng tiền</span>
                                <strong>{{ number_format($tongTien, 0, ',', '.') }} ₫</strong>
                            </div>

                            <button type="submit" class="btn-web-primary w-100 justify-content-center mt-3">
                                <i class="bi bi-check-circle"></i>
                                Đặt hàng
                            </button>

                            <a href="{{ route('web.giohang.index') }}" class="btn-web-light w-100 justify-content-center mt-2">
                                Quay lại giỏ hàng
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection
