document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('adminWrapper');
    const sidebar = document.getElementById('adminSidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const overlay = document.getElementById('adminOverlay');
    const pageLoading = document.getElementById('pageLoading');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            if (window.innerWidth >= 992) {
                wrapper?.classList.toggle('sidebar-collapsed');
                luuTrangThaiSidebar(wrapper?.classList.contains('sidebar-collapsed'));
            } else {
                moSidebarMobile();
            }
        });
    }

    if (sidebarClose) {
        sidebarClose.addEventListener('click', dongSidebarMobile);
    }

    if (overlay) {
        overlay.addEventListener('click', dongSidebarMobile);
    }

    function moSidebarMobile() {
        sidebar?.classList.add('show');
        overlay?.classList.add('show');
        document.body.classList.add('admin-menu-open');
    }

    function dongSidebarMobile() {
        sidebar?.classList.remove('show');
        overlay?.classList.remove('show');
        document.body.classList.remove('admin-menu-open');
    }

    function luuTrangThaiSidebar(isCollapsed) {
        localStorage.setItem('admin_sidebar_collapsed', isCollapsed ? '1' : '0');
    }

    function khoiPhucTrangThaiSidebar() {
        if (window.innerWidth < 992) {
            return;
        }

        const collapsed = localStorage.getItem('admin_sidebar_collapsed') === '1';

        if (collapsed) {
            wrapper?.classList.add('sidebar-collapsed');
        }
    }

    khoiPhucTrangThaiSidebar();

    document.addEventListener('submit', function (event) {
        const form = event.target.closest('form[data-confirm]');

        if (!form) {
            return;
        }

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
                popup: 'swal-admin-popup',
                title: 'swal-admin-title',
                htmlContainer: 'swal-admin-text',
                confirmButton: 'swal-admin-confirm',
                cancelButton: 'swal-admin-cancel'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.isConfirmed) {
                form.removeAttribute('data-confirm');

                const pageLoading = document.getElementById('pageLoading');

                if (pageLoading) {
                    pageLoading.classList.add('show');
                }

                form.submit();
            }
        });
    });

    document.querySelectorAll('form:not([data-confirm])').forEach(function (form) {
        form.addEventListener('submit', function () {
            const skipLoading = form.dataset.skipLoading === 'true';

            if (!skipLoading && pageLoading) {
                pageLoading.classList.add('show');
            }

            form.querySelectorAll('button[type="submit"]').forEach(function (button) {
                if (!button.dataset.noDisable) {
                    button.disabled = true;

                    if (!button.dataset.originalText) {
                        button.dataset.originalText = button.innerHTML;
                    }

                    if (!button.classList.contains('btn-nho') && !button.classList.contains('btn-nguyhiem')) {
                        button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang xử lý';
                    }
                }
            });
        });
    });

    const formLocDanhMuc = document.getElementById('formLocDanhMuc');
    const danhMucKetQuaWrap = document.getElementById('danhMucKetQuaWrap');

    if (formLocDanhMuc && danhMucKetQuaWrap) {
        let filterTimer = null;

        formLocDanhMuc.querySelectorAll('.auto-filter').forEach(function (select) {
            select.addEventListener('change', function () {
                locDanhMucAjax();
            });
        });

        const inputTuKhoa = formLocDanhMuc.querySelector('input[name="tu_khoa"]');

        if (inputTuKhoa) {
            inputTuKhoa.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    locDanhMucAjax();
                }
            });

            inputTuKhoa.addEventListener('input', function () {
                clearTimeout(filterTimer);

                filterTimer = setTimeout(function () {
                    locDanhMucAjax();
                }, 600);
            });
        }

        formLocDanhMuc.addEventListener('submit', function (event) {
            event.preventDefault();
            locDanhMucAjax();
        });

        document.addEventListener('click', function (event) {
            const link = event.target.closest('#danhMucKetQuaWrap .pagination a');

            if (!link) {
                return;
            }

            event.preventDefault();
            locDanhMucAjax(link.href);
        });

        document.addEventListener('click', function (event) {
            const sortButton = event.target.closest('#danhMucKetQuaWrap [data-sort-column]');

            if (!sortButton) {
                return;
            }

            event.preventDefault();

            const cotInput = document.getElementById('cotSapXepDanhMuc');
            const huongInput = document.getElementById('huongSapXepDanhMuc');

            if (!cotInput || !huongInput) {
                return;
            }

            const cotMoi = sortButton.dataset.sortColumn;
            const cotHienTai = cotInput.value;
            const huongHienTai = huongInput.value;

            cotInput.value = cotMoi;

            if (cotMoi === cotHienTai) {
                huongInput.value = huongHienTai === 'asc' ? 'desc' : 'asc';
            } else {
                huongInput.value = 'asc';
            }

            locDanhMucAjax();
        });
    }

    function locDanhMucAjax(customUrl = null) {
        const formLocDanhMuc = document.getElementById('formLocDanhMuc');
        const danhMucKetQuaWrap = document.getElementById('danhMucKetQuaWrap');

        if (!formLocDanhMuc || !danhMucKetQuaWrap) {
            return;
        }

        const formData = new FormData(formLocDanhMuc);
        const params = new URLSearchParams(formData);
        const url = customUrl || (formLocDanhMuc.action + '?' + params.toString());

        danhMucKetQuaWrap.classList.add('ajax-loading-soft');

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html',
            }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Không thể tải dữ liệu danh mục.');
                }

                return response.text();
            })
            .then(function (html) {
                danhMucKetQuaWrap.innerHTML = html;

                if (!customUrl) {
                    window.history.replaceState({}, '', url);
                } else {
                    window.history.replaceState({}, '', customUrl);
                }
            })
            .catch(function () {
                alert('Có lỗi xảy ra khi lọc danh mục. Vui lòng thử lại.');
            })
            .finally(function () {
                danhMucKetQuaWrap.classList.remove('ajax-loading-soft');
            });
    }

    const btnXoaLocDanhMuc = document.getElementById('btnXoaLocDanhMuc');

    if (btnXoaLocDanhMuc && formLocDanhMuc) {
        btnXoaLocDanhMuc.addEventListener('click', function () {
            formLocDanhMuc.reset();

            formLocDanhMuc.querySelectorAll('input, select').forEach(function (field) {
                if (field.name === 'sap_xep') {
                    field.value = 'thu_tu';
                } else if (field.name === 'cot_sap_xep') {
                    field.value = 'thu_tu';
                } else if (field.name === 'huong_sap_xep') {
                    field.value = 'asc';
                } else {
                    field.value = '';
                }
            });

            locDanhMucAjax(formLocDanhMuc.action);
        });
    }

    const toastThongBao = document.getElementById('toastThongBao');

    if (toastThongBao) {
        setTimeout(function () {
            toastThongBao.style.display = 'none';
        }, 3000);
    }

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
            dongSidebarMobile();
        }
    });
});
