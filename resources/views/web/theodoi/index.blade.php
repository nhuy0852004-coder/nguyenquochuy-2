@extends('web.layouts.app')

@section('tieude', 'Theo dõi đơn hàng')

@section('mota', 'Tra cứu trạng thái đơn hàng bằng mã đơn hàng hoặc số điện thoại.')

@section('noidung')
    <div class="container">
        <div class="breadcrumb-web">
            <a href="{{ route('web.trangchu') }}">Trang chủ</a>
            <span class="mx-2">/</span>
            <span>Theo dõi đơn hàng</span>
        </div>

        <section class="section-block">
            <div class="track-page">
                <div class="track-box">
                    <div class="track-icon">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>

                    <h1>Theo dõi đơn hàng</h1>

                    <p class="text-muted">
                        Nhập mã đơn hàng hoặc số điện thoại để kiểm tra trạng thái xử lý đơn hàng của bạn.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 rounded-3 text-start">
                            @foreach ($errors->all() as $loi)
                                <div>{{ $loi }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('web.theodoi.timkiem') }}" method="POST" class="track-form">
                        @csrf

                        <input
                            type="text"
                            name="tu_khoa"
                            value="{{ old('tu_khoa') }}"
                            placeholder="Ví dụ: DH26051310153088 hoặc 0901234567"
                            required
                        >

                        <button type="submit" class="btn-web-primary justify-content-center">
                            <i class="bi bi-search"></i>
                            Tra cứu đơn hàng
                        </button>
                    </form>

                    <div class="track-note">
                        <div>
                            <i class="bi bi-info-circle me-1"></i>
                            Mã đơn hàng được hiển thị sau khi bạn đặt hàng thành công.
                        </div>
                        <div>
                            <i class="bi bi-phone me-1"></i>
                            Nếu nhập số điện thoại, hệ thống sẽ hiển thị các đơn hàng liên quan.
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
