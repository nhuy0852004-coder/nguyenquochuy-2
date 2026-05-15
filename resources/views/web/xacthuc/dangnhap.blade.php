@extends('web.layouts.app')

@section('tieude', 'Đăng nhập')

@section('robots', 'noindex, follow')

@section('noidung')
    <div class="container">
        <section class="web-auth-section">
            <div class="web-auth-card">
                <div class="web-auth-icon">
                    <i class="bi bi-person"></i>
                </div>

                <h1>Đăng nhập</h1>

                <p class="text-muted">
                    Đăng nhập để theo dõi đơn hàng và mua hàng nhanh hơn.
                </p>

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
                    <div class="alert alert-danger border-0 rounded-3 text-start">
                        @foreach ($errors->all() as $loi)
                            <div>{{ $loi }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('web.xuly.dangnhap') }}" method="POST">
                    @csrf

                    <div class="mb-3 text-start">
                        <label class="form-label">Email hoặc số điện thoại</label>
                        <div class="web-auth-input">
                            <i class="bi bi-person"></i>
                            <input
                                type="text"
                                name="tai_khoan"
                                value="{{ old('tai_khoan') }}"
                                placeholder="Email hoặc số điện thoại"
                                required
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
                                placeholder="Nhập mật khẩu"
                                required
                            >
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-check-label d-flex align-items-center gap-2">
                            <input type="checkbox" name="ghi_nho" value="1" class="form-check-input m-0">
                            Ghi nhớ
                        </label>

                        <a href="{{ route('web.dangky') }}" class="text-decoration-none small">
                            Tạo tài khoản
                        </a>
                    </div>

                    <button type="submit" class="btn-web-primary w-100">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Đăng nhập
                    </button>

                    <div class="text-center my-3 text-muted small">Hoặc</div>

                    <a href="{{ route('web.dangnhap.google') }}" class="btn btn-light border w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-google"></i>
                        Đăng nhập bằng Google
                    </a>

                    <div class="text-muted small mt-3 text-start">
                        Nếu gặp lỗi redirect URI, hãy kiểm tra đúng URL callback trong Google Cloud Console.
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
