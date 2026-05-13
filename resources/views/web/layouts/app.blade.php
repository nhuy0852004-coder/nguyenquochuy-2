<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('tieude', 'Trang chủ') - Bán Hàng Việt</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f8fafc;">
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('web.trangchu') }}">
                Bán Hàng Việt
            </a>

            <div class="d-flex gap-3">
                <a href="#" class="text-decoration-none text-dark">Sản phẩm</a>
                <a href="#" class="text-decoration-none text-dark">Giỏ hàng</a>
                <a href="#" class="text-decoration-none text-dark">Theo dõi đơn</a>
            </div>
        </div>
    </nav>

    <main>
        @yield('noidung')
    </main>

    <footer class="bg-white border-top mt-5 py-4">
        <div class="container text-center text-muted">
            © {{ date('Y') }} Bán Hàng Việt. Hệ thống bán hàng chuẩn Việt Nam.
        </div>
    </footer>
</body>
</html>
