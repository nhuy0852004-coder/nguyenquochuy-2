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
                sidebar?.classList.add('show');
                overlay?.classList.add('show');
            }
        });
    }

    if (sidebarClose) {
        sidebarClose.addEventListener('click', dongSidebarMobile);
    }

    if (overlay) {
        overlay.addEventListener('click', dongSidebarMobile);
    }

    function dongSidebarMobile() {
        sidebar?.classList.remove('show');
        overlay?.classList.remove('show');
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

    document.querySelectorAll('form').forEach(function (form) {
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

    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const message = form.dataset.confirm || 'Bạn có chắc muốn thực hiện thao tác này không?';

            if (!confirm(message)) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });

    document.querySelectorAll('[data-confirm]:not(form)').forEach(function (element) {
        element.addEventListener('click', function (event) {
            const message = element.dataset.confirm || 'Bạn có chắc muốn thực hiện thao tác này không?';

            if (!confirm(message)) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });

    const toastThongBao = document.getElementById('toastThongBao');

    if (toastThongBao) {
        setTimeout(function () {
            toastThongBao.style.display = 'none';
        }, 3000);
    }
});
