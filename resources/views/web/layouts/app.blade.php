<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('tieude', 'Trang chủ') - {{ $caidatcuahang->ten_cua_hang ?? 'Bán Hàng Việt' }}</title>

    <meta name="description" content="@yield('mota', 'Website bán hàng thời trang chuẩn Việt Nam, giá tốt, giao hàng toàn quốc và thanh toán khi nhận hàng.')">
    <meta name="robots" content="@yield('robots', 'index, follow')">

    <link rel="canonical" href="@yield('canonical', url()->current())">

    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('tieude', 'Trang chủ')) . ' - ' . ($caidatcuahang->ten_cua_hang ?? 'Bán Hàng Việt'))">
    <meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('mota', 'Website bán hàng thời trang chuẩn Việt Nam.')))">
    <meta property="og:url" content="@yield('canonical', url()->current())">

    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
    @endif

    <meta name="theme-color" content="#2563EB">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/web.css') }}" rel="stylesheet">

    @vite(['resources/js/app.js'])
</head>

<body>
    @include('web.layouts.header')

    <div class="web-toast" id="webToast">
        <i class="bi bi-check-circle-fill"></i>
        <span id="webToastText">Thông báo mới.</span>
    </div>

    <main>
        @yield('noidung')
    </main>

    @include('web.layouts.footer')

    <div class="web-mobile-overlay" id="webMobileOverlay"></div>

    <div class="web-page-loading" id="webPageLoading">
        <div class="web-page-loading-box">
            <div class="spinner-border spinner-border-sm text-primary"></div>
            <span>Đang xử lý...</span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/web.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof window.maDonHangTheoDoi === 'undefined') {
                return;
            }

            if (typeof window.Echo === 'undefined') {
                console.warn('Laravel Echo chưa sẵn sàng ở website.');
                return;
            }

            window.Echo.channel('donhang-' + window.maDonHangTheoDoi)
                .listen('.cap-nhat-trang-thai', function (event) {
                    if (!event.don_hang) {
                        return;
                    }

                    capNhatBadgeTrangThai(event.don_hang);
                    capNhatTimelineDonHang(event.don_hang.timeline || []);
                    hienThiToastWeb('Đơn hàng của bạn đã được cập nhật: ' + event.don_hang.trang_thai_text);
                });

            function capNhatBadgeTrangThai(donHang) {
                const badge = document.getElementById('trangThaiDonHangBadge');

                if (!badge) {
                    return;
                }

                badge.className = 'track-status ' + donHang.trang_thai_class;
                badge.textContent = donHang.trang_thai_text;
                badge.classList.add('track-status-pulse');

                setTimeout(function () {
                    badge.classList.remove('track-status-pulse');
                }, 800);
            }

            function capNhatTimelineDonHang(timeline) {
                const timelineBox = document.getElementById('timelineDonHang');

                if (!timelineBox || !Array.isArray(timeline)) {
                    return;
                }

                timelineBox.innerHTML = '';

                timeline.forEach(function (item) {
                    const itemDiv = document.createElement('div');

                    let className = 'timeline-item';

                    if (item.done) {
                        className += ' done';
                    }

                    if (item.active) {
                        className += ' active';
                    }

                    itemDiv.className = className;

                    itemDiv.innerHTML = `
                        <div class="timeline-dot">
                            ${item.done ? '<i class="bi bi-check"></i>' : ''}
                        </div>

                        <div>
                            <div class="timeline-title">${escapeHtml(item.label)}</div>
                            <div class="timeline-desc">${escapeHtml(item.mo_ta)}</div>
                        </div>
                    `;

                    timelineBox.appendChild(itemDiv);
                });
            }

            function hienThiToastWeb(noiDung) {
                const toast = document.getElementById('webToast');
                const text = document.getElementById('webToastText');

                if (!toast || !text) {
                    return;
                }

                text.textContent = noiDung;
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
