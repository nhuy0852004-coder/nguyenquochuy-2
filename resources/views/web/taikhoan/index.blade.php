@extends('web.layouts.app')

@section('tieude', 'Tài khoản của tôi')

@section('robots', 'noindex, follow')

@section('noidung')
    <div class="container">
        <div class="breadcrumb-web">
            <a href="{{ route('web.trangchu') }}">Trang chủ</a>
            <span class="mx-2">/</span>
            <span>Tài khoản</span>
        </div>

        <section class="section-block">
            <div class="account-page-head">
                <div>
                    <h1>Tài khoản của tôi</h1>
                    <p>Quản lý thông tin cá nhân và theo dõi lịch sử đơn hàng.</p>
                </div>

                <form action="{{ route('web.dangxuat') }}" method="POST">
                    @csrf

                    <button type="submit" class="btn-web-light">
                        <i class="bi bi-box-arrow-right"></i>
                        Đăng xuất
                    </button>
                </form>
            </div>

            @if (session('thanhcong'))
                <div class="alert alert-success border-0 rounded-3">
                    {{ session('thanhcong') }}
                </div>
            @endif

            <div class="account-layout">
                <div class="account-card">
                    <div class="account-avatar">
                        {{ mb_substr($khachhang->ho_ten, 0, 1) }}
                    </div>

                    <h2>{{ $khachhang->ho_ten }}</h2>

                    <div class="text-muted mb-3">
                        Khách hàng #{{ str_pad($khachhang->id, 5, '0', STR_PAD_LEFT) }}
                    </div>

                    <div class="account-line">
                        <span>Số điện thoại</span>
                        <strong>{{ $khachhang->so_dien_thoai }}</strong>
                    </div>

                    <div class="account-line">
                        <span>Email</span>
                        <strong>{{ $khachhang->email ?: 'Chưa cập nhật' }}</strong>
                    </div>

                    <div class="account-line">
                        <span>Địa chỉ</span>
                        <strong>{{ $khachhang->dia_chi ?: 'Chưa cập nhật' }}</strong>
                    </div>

                    <div class="account-line">
                        <span>Tổng đơn</span>
                        <strong>{{ $khachhang->donhang->count() }}</strong>
                    </div>

                    <div class="account-line">
                        <span>Tổng tiền đã mua</span>
                        <strong>{{ number_format($khachhang->tongTienDaMua(), 0, ',', '.') }} ₫</strong>
                    </div>
                </div>

                <div class="account-card account-orders">
                    <div class="account-card-head">
                        <div>
                            <h2>Lịch sử đơn hàng</h2>
                            <p>10 đơn hàng gần nhất của bạn.</p>
                        </div>
                    </div>

                    @forelse ($khachhang->donhang as $donhang)
                        <div class="account-order-item">
                            <div>
                                <div class="fw-bold">{{ $donhang->ma_don_hang }}</div>
                                <div class="text-muted small">
                                    {{ $donhang->created_at->format('d/m/Y H:i') }}
                                    · {{ $donhang->chitietdonhang->sum('so_luong') }} sản phẩm
                                </div>
                            </div>

                            <div class="text-end">
                                <div class="fw-bold">
                                    {{ number_format($donhang->tong_tien, 0, ',', '.') }} ₫
                                </div>

                                <span class="track-status {{ $donhang->trangThaiDonHangClass() }}">
                                    {{ $donhang->trangThaiDonHangText() }}
                                </span>
                            </div>

                            <a href="{{ route('web.theodoi.chitiet', $donhang->ma_don_hang) }}" class="btn-web-light">
                                Xem
                            </a>
                        </div>
                    @empty
                        <div class="empty-web">
                            <div class="empty-web-icon">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <h5 class="fw-bold">Chưa có đơn hàng</h5>
                            <p class="text-muted mb-0">Các đơn hàng của bạn sẽ hiển thị tại đây.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
@endsection
