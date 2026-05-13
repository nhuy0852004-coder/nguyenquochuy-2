<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('tieude', 'Trang chủ') - Bán Hàng Việt</title>

    <meta name="description" content="@yield('mota', 'Website bán hàng thời trang chuẩn Việt Nam')">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/web.css') }}" rel="stylesheet">
    @vite(['resources/js/app.js'])
</head>

<body>
    @include('web.layouts.header')

    <div class="web-toast" id="webToast">
        <i class="bi bi-check-circle-fill"></i>
        <span id="webToastText">Đã thêm sản phẩm vào giỏ hàng.</span>
    </div>

    <main>
        @yield('noidung')
    </main>

    @include('web.layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('.form-them-gio');
        const soLuongGioHang = document.getElementById('soLuongGioHang');
        const nutGioHangHeader = document.getElementById('nutGioHangHeader');
        const webToast = document.getElementById('webToast');
        const webToastText = document.getElementById('webToastText');

        forms.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const button = form.querySelector('button[type="submit"]');
                const buttonTextCu = button.innerHTML;
                const cardSanPham = form.closest('.sanpham-card-bay');

                button.disabled = true;
                button.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Đang thêm';

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        if (data.success) {
                            bayVaoGioHang(cardSanPham);

                            setTimeout(function () {
                                if (soLuongGioHang) {
                                    soLuongGioHang.textContent = data.so_luong_gio_hang;
                                }

                                if (nutGioHangHeader) {
                                    nutGioHangHeader.classList.add('gio-rung');

                                    setTimeout(function () {
                                        nutGioHangHeader.classList.remove('gio-rung');
                                    }, 450);
                                }

                                hienThiToast(data.message || 'Đã thêm sản phẩm vào giỏ hàng.');
                            }, 520);
                        } else {
                            hienThiToast(data.message || 'Không thể thêm sản phẩm vào giỏ hàng.');
                        }
                    })
                    .catch(function () {
                        hienThiToast('Có lỗi xảy ra, vui lòng thử lại.');
                    })
                    .finally(function () {
                        setTimeout(function () {
                            button.disabled = false;
                            button.innerHTML = buttonTextCu;
                        }, 520);
                    });
            });
        });

        function bayVaoGioHang(cardSanPham) {
            if (!cardSanPham || !nutGioHangHeader) {
                return;
            }

            const anhSanPham = cardSanPham.querySelector('.anh-bay-gio');
            const viTriGioHang = nutGioHangHeader.getBoundingClientRect();

            let phanTuBay;

            if (anhSanPham) {
                const viTriAnh = anhSanPham.getBoundingClientRect();

                phanTuBay = anhSanPham.cloneNode(true);
                phanTuBay.classList.add('sanpham-bay-gio');

                phanTuBay.style.left = viTriAnh.left + 'px';
                phanTuBay.style.top = viTriAnh.top + 'px';
                phanTuBay.style.width = viTriAnh.width + 'px';
                phanTuBay.style.height = viTriAnh.height + 'px';
            } else {
                const viTriCard = cardSanPham.getBoundingClientRect();

                phanTuBay = document.createElement('div');
                phanTuBay.classList.add('sanpham-bay-gio', 'sanpham-bay-gio-rong');
                phanTuBay.innerHTML = '<i class="bi bi-bag"></i>';

                phanTuBay.style.left = viTriCard.left + 40 + 'px';
                phanTuBay.style.top = viTriCard.top + 40 + 'px';
                phanTuBay.style.width = '80px';
                phanTuBay.style.height = '80px';
            }

            document.body.appendChild(phanTuBay);

            phanTuBay.getBoundingClientRect();

            const dichX = viTriGioHang.left + viTriGioHang.width / 2 - parseFloat(phanTuBay.style.left) - 24;
            const dichY = viTriGioHang.top + viTriGioHang.height / 2 - parseFloat(phanTuBay.style.top) - 24;

            phanTuBay.style.transform = `translate(${dichX}px, ${dichY}px) scale(0.12) rotate(12deg)`;
            phanTuBay.style.opacity = '0.15';

            setTimeout(function () {
                phanTuBay.remove();
            }, 650);
        }

        function hienThiToast(noiDung) {
            if (!webToast || !webToastText) {
                return;
            }

            webToastText.textContent = noiDung;
            webToast.classList.add('show');

            setTimeout(function () {
                webToast.classList.remove('show');
            }, 2500);
        }
    });
</script>

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
                    console.log('Realtime trạng thái đơn hàng:', event);

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
