<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng nhập Admin - Hệ thống bán hàng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>

<body>
    <div class="auth-admin-page">
        <div class="auth-admin-card">
            <div class="auth-admin-logo">
                <i class="bi bi-shop"></i>
            </div>

            <h1>Đăng nhập Admin</h1>

            <p class="text-muted">
                Vui lòng đăng nhập để truy cập hệ thống quản trị bán hàng.
            </p>

            @if (session('thanhcong'))
                <div class="alert alert-success border-0 rounded-3">
                    {{ session('thanhcong') }}
                </div>
            @endif

            @if (session('loi'))
                <div class="alert alert-danger border-0 rounded-3">
                    {{ session('loi') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-3 text-start">
                    @foreach ($errors->all() as $loi)
                        <div>{{ $loi }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.xuly.dangnhap') }}" method="POST">
                @csrf

                <div class="mb-3 text-start">
                    <label class="form-label">Email</label>
                    <div class="auth-input">
                        <i class="bi bi-envelope"></i>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="admin@cuahang.vn"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label">Mật khẩu</label>
                    <div class="auth-input">
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
                        Ghi nhớ đăng nhập
                    </label>

                    <a href="{{ route('web.trangchu') }}" class="text-decoration-none small">
                        Về website
                    </a>
                </div>

                <button type="submit" class="btn-chinh w-100 justify-content-center">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Đăng nhập
                </button>
            </form>

            <div class="auth-admin-note">
                Tài khoản mặc định: <strong>admin@cuahang.vn</strong>
                <br>
                Mật khẩu: <strong>12345678</strong>
            </div>
        </div>
    </div>
</body>
</html>
