document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuBtn = document.getElementById('webMobileMenuBtn');
    const mobileMenu = document.getElementById('webMobileMenu');
    const mobileMenuClose = document.getElementById('webMobileMenuClose');
    const mobileOverlay = document.getElementById('webMobileOverlay');
    const searchMobileBtn = document.getElementById('webSearchMobileBtn');
    const mobileSearch = document.getElementById('webMobileSearch');
    const pageLoading = document.getElementById('webPageLoading');

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function () {
            mobileMenu?.classList.add('show');
            mobileOverlay?.classList.add('show');
        });
    }

    if (mobileMenuClose) {
        mobileMenuClose.addEventListener('click', dongMobileMenu);
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', dongMobileMenu);
    }

    function dongMobileMenu() {
        mobileMenu?.classList.remove('show');
        mobileOverlay?.classList.remove('show');
    }

    if (searchMobileBtn) {
        searchMobileBtn.addEventListener('click', function () {
            mobileSearch?.classList.toggle('show');

            if (mobileSearch?.classList.contains('show')) {
                mobileSearch.querySelector('input')?.focus();
            }
        });
    }

    document.querySelectorAll('.form-them-gio').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const button = form.querySelector('button[type="submit"]');
            const buttonTextCu = button.innerHTML;
            const cardSanPham = form.closest('.sanpham-card-bay');

            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang thêm';

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
                            const soLuongGioHang = document.getElementById('soLuongGioHang');
                            const nutGioHangHeader = document.getElementById('nutGioHangHeader');

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

    document.querySelectorAll('form:not(.form-them-gio)').forEach(function (form) {
        form.addEventListener('submit', function () {
            if (form.dataset.skipLoading === 'true') {
                return;
            }

            if (pageLoading) {
                pageLoading.classList.add('show');
            }
        });
    });

    function bayVaoGioHang(cardSanPham) {
        const nutGioHangHeader = document.getElementById('nutGioHangHeader');

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
        const toast = document.getElementById('webToast');
        const text = document.getElementById('webToastText');

        if (!toast || !text) {
            return;
        }

        text.textContent = noiDung;
        toast.classList.add('show');

        setTimeout(function () {
            toast.classList.remove('show');
        }, 2500);
    }
});
