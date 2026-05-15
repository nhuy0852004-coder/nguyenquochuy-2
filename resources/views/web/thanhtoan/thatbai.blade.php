@extends('web.layouts.app')

@section('tieude', 'Thanh toán thất bại')

@section('noidung')
    <div class="container">
        <section class="section-block">
            <div class="success-box success-box-pro">
                <div class="success-icon" style="background:#fee2e2;color:#dc2626;">
                    <i class="bi bi-x-circle"></i>
                </div>

                <h1>Thanh toán không thành công</h1>

                <p class="text-muted">
                    Giao dịch chưa được hoàn tất hoặc đã bị hủy. Bạn có thể quay lại giỏ hàng hoặc tiếp tục mua sắm.
                </p>

                @if (session('loi'))
                    <div class="alert alert-danger border-0 rounded-3 mt-3">
                        {{ session('loi') }}
                    </div>
                @endif

                <div class="d-flex justify-content-center gap-2 flex-wrap mt-4">
                    <a href="{{ route('web.giohang.index') }}" class="btn-web-primary">
                        Quay lại giỏ hàng
                    </a>

                    <a href="{{ route('web.sanpham.index') }}" class="btn-web-light">
                        Tiếp tục mua hàng
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection