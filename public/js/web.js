document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuBtn = document.getElementById('webMobileMenuBtn');
    const mobileMenu = document.getElementById('webMobileMenu');
    const mobileMenuClose = document.getElementById('webMobileMenuClose');
    const mobileOverlay = document.getElementById('webMobileOverlay');
    const searchMobileBtn = document.getElementById('webSearchMobileBtn');
    const mobileSearch = document.getElementById('webMobileSearch');
    const productFilterToggle = document.getElementById('productFilterToggle');
    const productSidebarFilter = document.getElementById('productSidebarFilter');
    const productFilterClose = document.getElementById('productFilterClose');
    const pageLoading = document.getElementById('webPageLoading');

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function () {
            mobileMenu?.classList.add('show');
            mobileOverlay?.classList.add('show');
            document.body.classList.add('web-menu-open');
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
        document.body.classList.remove('web-menu-open');
    }

    if (searchMobileBtn) {
        searchMobileBtn.addEventListener('click', function () {
            mobileSearch?.classList.toggle('show');

            if (mobileSearch?.classList.contains('show')) {
                mobileSearch.querySelector('input')?.focus();
            }
        });
    }

    if (productFilterToggle) {
        productFilterToggle.addEventListener('click', function () {
            productSidebarFilter?.classList.add('show');
            mobileOverlay?.classList.add('show');
            document.body.classList.add('web-menu-open');
        });
    }

    if (productFilterClose) {
        productFilterClose.addEventListener('click', dongProductFilter);
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', dongProductFilter);
    }

    function dongProductFilter() {
        productSidebarFilter?.classList.remove('show');
        mobileOverlay?.classList.remove('show');
        document.body.classList.remove('web-menu-open');
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

                            hienThiToast(data.message || 'Đã thêm sản phẩm vào giỏ hàng.', 'success');
                        }, 520);
                    } else {
                        hienThiToast(data.message || 'Không thể thêm sản phẩm vào giỏ hàng.', 'error');
                    }
                })
                .catch(function () {
                    hienThiToast('Có lỗi xảy ra, vui lòng thử lại.', 'error');
                })
                .finally(function () {
                    setTimeout(function () {
                        button.disabled = false;
                        button.innerHTML = buttonTextCu;
                    }, 520);
                });
        });
    });

    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const title = form.dataset.confirmTitle || 'Xác nhận thao tác';
            const text = form.dataset.confirm || 'Bạn có chắc muốn thực hiện thao tác này không?';
            const icon = form.dataset.confirmIcon || 'warning';
            const confirmText = form.dataset.confirmButton || 'Đồng ý';
            const cancelText = form.dataset.cancelButton || 'Hủy';

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'swal-web-popup',
                    title: 'swal-web-title',
                    htmlContainer: 'swal-web-text',
                    confirmButton: 'swal-web-confirm',
                    cancelButton: 'swal-web-cancel'
                },
                buttonsStyling: false
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.removeAttribute('data-confirm');
                    form.submit();
                }
            });
        });
    });

    document.querySelectorAll('form:not(.form-them-gio):not([data-confirm])').forEach(function (form) {
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

    function hienThiToast(noiDung, type = 'success') {
        const toast = document.getElementById('webToast');
        const text = document.getElementById('webToastText');

        if (!toast || !text) {
            return;
        }

        text.textContent = noiDung;
        toast.classList.remove('is-error', 'is-success');
        toast.classList.add(type === 'error' ? 'is-error' : 'is-success');
        toast.classList.add('show');

        setTimeout(function () {
            toast.classList.remove('show');
        }, 2500);
    }
});
