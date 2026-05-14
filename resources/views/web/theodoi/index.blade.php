@extends('web.layouts.app')

@section('tieude', 'Theo dõi đơn hàng')

@section('mota', 'Tra cứu trạng thái đơn hàng bằng mã đơn hàng hoặc số điện thoại, cập nhật trạng thái đơn hàng nhanh chóng.')

@section('canonical', route('web.theodoi.index'))

@section('robots', 'noindex, follow')

@section('noidung')
    <div class="container">
        <div class="breadcrumb-web">
            <a href="{{ route('web.trangchu') }}">Trang chủ</a>
            <span class="mx-2">/</span>
            <span>Theo dõi đơn hàng</span>
        </div>

        <section class="track-hero-section">
            <div class="track-hero-box">
                <div class="row g-0 align-items-center">
                    <div class="col-lg-6">
                        <div class="track-hero-content">
                            <div class="track-hero-label">
                                <i class="bi bi-receipt-cutoff"></i>
                                Tra cứu đơn hàng
                            </div>

                            <h1>Theo dõi trạng thái đơn hàng của bạn</h1>

                            <p>
                                Nhập mã đơn hàng hoặc số điện thoại đã đặt hàng để kiểm tra tình trạng xử lý,
                                giao hàng và thông tin đơn hàng.
                            </p>

                            <div class="track-benefits">
                                <div>
                                    <i class="bi bi-check-circle"></i>
                                    Cập nhật trạng thái realtime
                                </div>

                                <div>
                                    <i class="bi bi-check-circle"></i>
                                    Không cần đăng nhập tài khoản
                                </div>

                                <div>
                                    <i class="bi bi-check-circle"></i>
                                    Xem lại sản phẩm và tổng tiền
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="track-search-panel">
                            <div class="track-search-icon">
                                <i class="bi bi-search"></i>
                            </div>

                            <h2>Tra cứu nhanh</h2>

                            <p class="text-muted">
                                Bạn có thể nhập mã đơn hàng hoặc số điện thoại để xem các đơn đã đặt.
                            </p>

                            @if ($errors->any())
                                <div class="alert alert-danger border-0 rounded-3 text-start">
                                    @foreach ($errors->all() as $loi)
                                        <div>{{ $loi }}</div>
                                    @endforeach
                                </div>
                            @endif

                            @if (session('loi'))
                                <div class="alert alert-danger border-0 rounded-3 text-start">
                                    {{ session('loi') }}
                                </div>
                            @endif

                            <form action="{{ route('web.theodoi.timkiem') }}" method="POST" class="track-search-form">
                                @csrf

                                <label>Mã đơn hàng hoặc số điện thoại</label>

                                <div class="track-search-input">
                                    <i class="bi bi-receipt"></i>
                                    <input
                                        type="text"
                                        name="tu_khoa"
                                        value="{{ old('tu_khoa') }}"
                                        placeholder="Ví dụ: DH26051310153088 hoặc 0901234567"
                                        required
                                    >
                                </div>

                                <button type="submit" class="btn-web-primary w-100 justify-content-center">
                                    <i class="bi bi-search"></i>
                                    Tra cứu đơn hàng
                                </button>
                            </form>

                            <div class="track-search-note">
                                <div>
                                    <i class="bi bi-info-circle"></i>
                                    Mã đơn hàng được hiển thị sau khi đặt hàng thành công.
                                </div>

                                <div>
                                    <i class="bi bi-phone"></i>
                                    Nếu nhập số điện thoại, hệ thống sẽ hiển thị các đơn liên quan.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="track-guide-section">
            <div class="section-head">
                <div>
                    <h2>Quy trình xử lý đơn hàng</h2>
                    <p>Các trạng thái phổ biến khi đơn hàng được xử lý.</p>
                </div>
            </div>

            <div class="track-guide-grid">
                <div class="track-guide-card">
                    <div class="track-guide-icon warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <h3>Chờ xác nhận</h3>
                    <p>Cửa hàng đã nhận đơn và đang chờ kiểm tra thông tin.</p>
                </div>

                <div class="track-guide-card">
                    <div class="track-guide-icon primary">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <h3>Đã xác nhận</h3>
                    <p>Đơn hàng đã được xác nhận và chuẩn bị đóng gói.</p>
                </div>

                <div class="track-guide-card">
                    <div class="track-guide-icon info">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h3>Đang giao hàng</h3>
                    <p>Đơn hàng đang được vận chuyển đến địa chỉ của bạn.</p>
                </div>

                <div class="track-guide-card">
                    <div class="track-guide-icon success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h3>Hoàn thành</h3>
                    <p>Đơn hàng đã được giao thành công.</p>
                </div>
            </div>
        </section>
    </div>
@endsection
