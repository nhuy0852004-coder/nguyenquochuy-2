@extends('web.layouts.app')

@section('tieude', 'Đăng ký')

@section('robots', 'noindex, follow')

@section('noidung')
    <div class="container">
        <section class="web-auth-section">
            <div class="web-auth-card">
                <div class="web-auth-icon">
                    <i class="bi bi-person-plus"></i>
                </div>

                <h1>Tạo tài khoản</h1>

                <p class="text-muted">
                    Tạo tài khoản để theo dõi đơn hàng và mua hàng nhanh hơn.
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 text-start">
                        @foreach ($errors->all() as $loi)
                            <div>{{ $loi }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('web.xuly.dangky') }}" method="POST">
                    @csrf

                    <div class="mb-3 text-start">
                        <label class="form-label">Họ tên</label>
                        <div class="web-auth-input">
                            <i class="bi bi-person"></i>
                            <input
                                type="text"
                                name="ho_ten"
                                value="{{ old('ho_ten') }}"
                                placeholder="Nguyễn Văn An"
                                required
                            >
                        </div>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label">Số điện thoại</label>
                        <div class="web-auth-input">
                            <i class="bi bi-phone"></i>
                            <input
                                type="text"
                                name="so_dien_thoai"
                                value="{{ old('so_dien_thoai') }}"
                                placeholder="0901234567"
                                required
                            >
                        </div>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label">Email</label>
                        <div class="web-auth-input">
                            <i class="bi bi-envelope"></i>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="email@gmail.com"
                            >
                        </div>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label">Mật khẩu</label>
                        <div class="web-auth-input">
                            <i class="bi bi-lock"></i>
                            <input
                                type="password"
                                name="mat_khau"
                                placeholder="Tối thiểu 8 ký tự"
                                required
                            >
                        </div>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <div class="web-auth-input">
                            <i class="bi bi-lock"></i>
                            <input
                                type="password"
                                name="mat_khau_confirmation"
                                placeholder="Nhập lại mật khẩu"
                                required
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn-web-primary w-100">
                        <i class="bi bi-person-plus"></i>
                        Đăng ký
                    </button>

                    <div class="text-center mt-3">
                        <span class="text-muted">Đã có tài khoản?</span>
                        <a href="{{ route('web.dangnhap') }}" class="text-decoration-none fw-bold">
                            Đăng nhập
                        </a>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
