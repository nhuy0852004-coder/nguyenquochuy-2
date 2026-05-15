<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('tieude', 'Admin') - Hệ thống bán hàng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    @vite(['resources/js/app.js'])
</head>
<body>
    <div class="admin-realtime-toast" id="adminRealtimeToast">
        <div class="admin-realtime-toast-icon">
            <i class="bi bi-bell-fill"></i>
        </div>
        <div>
            <div class="fw-bold" id="adminRealtimeToastTitle">Thông báo mới</div>
            <div class="text-muted" id="adminRealtimeToastText">Bạn có thông báo mới.</div>
        </div>
    </div>

    <div class="admin-overlay" id="adminOverlay"></div>

    <div class="admin-wrapper" id="adminWrapper">
        @include('admin.layouts.sidebar')

        <main class="admin-main">
            @include('admin.layouts.header')

            <section class="admin-content">
                @yield('noidung')
            </section>
        </main>
    </div>

    <div class="page-loading" id="pageLoading">
        <div class="page-loading-box">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <span>Đang xử lý...</span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="{{ asset('js/admin.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof window.Echo === 'undefined') {
                console.warn('Laravel Echo chưa sẵn sàng. Kiểm tra npm run dev và resources/js/bootstrap.js.');
                return;
            }

            window.Echo.channel('admin-thongbao')
                .listen('.donhang-moi', function (event) {
                    console.log('Realtime đơn hàng mới:', event);

                    capNhatChuongThongBao(event.thong_bao);
                    hienThiToastRealtime(event.thong_bao);
                    themThongBaoVaoDropdown(event.thong_bao);
                });

            function capNhatChuongThongBao(thongBao) {
                const soThongBao = document.getElementById('adminSoThongBao');
                const textChuaDoc = document.getElementById('adminTextThongBaoChuaDoc');
                const dot = document.getElementById('adminDotThongBao');

                let soHienTai = 0;

                if (soThongBao) {
                    soHienTai = parseInt(soThongBao.textContent) || 0;
                    soHienTai += 1;
                    soThongBao.textContent = soHienTai > 99 ? '99+' : soHienTai;
                    soThongBao.style.display = 'grid';
                }

                if (dot) {
                    dot.style.display = 'block';
                }

                if (textChuaDoc) {
                    textChuaDoc.textContent = soHienTai;
                }

                const nutChuong = document.querySelector('.header-icon');

                if (nutChuong) {
                    nutChuong.classList.add('chuong-rung');

                    setTimeout(function () {
                        nutChuong.classList.remove('chuong-rung');
                    }, 600);
                }
            }

            function themThongBaoVaoDropdown(thongBao) {
                const list = document.getElementById('adminThongBaoList');
                const rong = document.getElementById('adminThongBaoRong');

                if (!list) {
                    return;
                }

                if (rong) {
                    rong.remove();
                }

                const item = document.createElement('div');

                item.innerHTML = `
                    <button type="button" class="thongbao-dropdown-item chua-doc" onclick="window.location.href='${thongBao.duong_dan}'">
                        <span class="thongbao-icon thongbao-donhang">
                            <i class="bi bi-receipt"></i>
                        </span>

                        <span class="thongbao-dropdown-content">
                            <strong>${escapeHtml(thongBao.tieu_de)}</strong>
                            <small>Vừa xong</small>
                        </span>
                    </button>
                `;

                list.prepend(item);
            }

            function hienThiToastRealtime(thongBao) {
                const toast = document.getElementById('adminRealtimeToast');
                const title = document.getElementById('adminRealtimeToastTitle');
                const text = document.getElementById('adminRealtimeToastText');

                if (!toast || !title || !text) {
                    return;
                }

                title.textContent = thongBao.tieu_de || 'Thông báo mới';
                text.textContent = thongBao.noi_dung || 'Bạn có thông báo mới.';

                toast.classList.add('show');

                setTimeout(function () {
                    toast.classList.remove('show');
                }, 3500);
            }

            function escapeHtml(value) {
                return String(value)
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
