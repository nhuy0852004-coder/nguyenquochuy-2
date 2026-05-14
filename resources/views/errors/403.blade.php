<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Không có quyền truy cập</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f5f7fa;">
    <div class="min-vh-100 d-flex align-items-center justify-content-center px-3">
        <div class="bg-white border rounded-4 p-5 text-center" style="max-width:520px;">
            <div class="display-5 fw-bold text-danger mb-2">403</div>

            <h1 class="h4 fw-bold mb-2">Bạn không có quyền truy cập</h1>

            <p class="text-muted mb-4">
                Tài khoản của bạn không được phép sử dụng chức năng này.
                Vui lòng liên hệ quản trị viên nếu cần cấp quyền.
            </p>

            <a href="{{ route('admin.bangdieukhien') }}" class="btn btn-primary">
                Về bảng điều khiển
            </a>
        </div>
    </div>
</body>
</html>
