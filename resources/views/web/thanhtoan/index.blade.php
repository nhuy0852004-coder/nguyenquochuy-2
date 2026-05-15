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
            <div class="checkout-page-head">
                <div>
                    <h1>Thanh toán</h1>
                    <p>Hoàn tất thông tin giao hàng để cửa hàng xử lý đơn của bạn.</p>
                </div>

                <a href="{{ route('web.giohang.index') }}" class="btn-web-light">
                    <i class="bi bi-arrow-left"></i>
                    Quay lại giỏ hàng
                </a>
            </div>

            @if (session('loi'))
                <div class="alert alert-danger border-0 rounded-3">
                    {{ session('loi') }}
                </div>
            @endif

            @if (session('thanhcong'))
                <div class="alert alert-success border-0 rounded-3">
                    {{ session('thanhcong') }}
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

            <form action="{{ route('web.thanhtoan.dathang') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="checkout-box checkout-box-pro">
                            <div class="checkout-section-head">
                                <div class="checkout-section-icon">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div>
                                    <h5>Thông tin người nhận</h5>
                                    <p>Nhập chính xác thông tin để đơn hàng được giao đúng.</p>
                                </div>
                            </div>

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
                                        placeholder="Số nhà, phường/xã, quận/huyện, tỉnh/thành"
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

                        <div class="checkout-box checkout-box-pro mt-3">
                            <div class="checkout-section-head">
                                <div class="checkout-section-icon">
                                    <i class="bi bi-credit-card"></i>
                                </div>
                                <div>
                                    <h5>Phương thức thanh toán</h5>
                                    <p>Chọn cách thanh toán phù hợp với bạn.</p>
                                </div>
                            </div>

                            <label class="payment-option payment-option-pro">
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

                            <label class="payment-option payment-option-pro">
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

                            <label class="payment-option payment-option-pro">
                                <input
                                    type="radio"
                                    name="phuong_thuc_thanh_toan"
                                    value="payos"
                                    {{ old('phuong_thuc_thanh_toan') === 'payos' ? 'checked' : '' }}
                                >
                                <div>
                                    <div class="fw-bold">Thanh toán online qua payOS / VietQR</div>
                                    <div class="text-muted small">
                                        Thanh toán bằng mã VietQR/ngân hàng qua cổng payOS. Hệ thống sẽ tự cập nhật sau khi thanh toán thành công.
                                    </div>
                                </div>
                            </label>

                            {{-- 
                            <label class="payment-option payment-option-pro">
                                <input
                                    type="radio"
                                    name="phuong_thuc_thanh_toan"
                                    value="vnpay"
                                    {{ old('phuong_thuc_thanh_toan') === 'vnpay' ? 'checked' : '' }}
                                >
                                <div>
                                    <div class="fw-bold">Thanh toán online VNPay Demo</div>
                                    <div class="text-muted small">
                                        Mô phỏng thanh toán online để demo quy trình. Không phát sinh giao dịch thật.
                                    </div>
                                </div>
                            </label>
                            --}}

                            <div class="bank-transfer-box">
                                <div class="fw-bold mb-2">
                                    <i class="bi bi-bank me-1"></i>
                                    Thông tin chuyển khoản demo
                                </div>

                                <div class="bank-line">
                                    <span>Ngân hàng</span>
                                    <strong>VCB - Vietcombank</strong>
                                </div>

                                <div class="bank-line">
                                    <span>Số tài khoản</span>
                                    <strong>0123456789</strong>
                                </div>

                                <div class="bank-line">
                                    <span>Chủ tài khoản</span>
                                    <strong>{{ $caidatcuahang->ten_cua_hang ?? 'BAN HANG VIET' }}</strong>
                                </div>

                                <div class="text-muted small mt-2">
                                    Nếu chọn chuyển khoản thủ công, vui lòng chuyển khoản theo thông tin trên.
                                    Nếu chọn VNPay, hệ thống sẽ chuyển bạn sang cổng thanh toán online.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="cart-summary checkout-summary-pro">
                            <h5 class="fw-bold mb-3">Đơn hàng của bạn</h5>

                            <div class="checkout-products">
                                @foreach ($giohang as $item)
                                    <div class="checkout-product checkout-product-pro">
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

                            <div class="cart-summary-note">
                                <div>
                                    <i class="bi bi-lock"></i>
                                    Thông tin của bạn được dùng để xử lý đơn hàng.
                                </div>

                                <div>
                                    <i class="bi bi-truck"></i>
                                    Cửa hàng sẽ liên hệ xác nhận trước khi giao.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection
