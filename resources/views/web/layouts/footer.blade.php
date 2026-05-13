<footer class="web-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5 class="fw-bold text-dark mb-3">
                    <i class="bi bi-shop me-1 text-primary"></i>
                    Bán Hàng Việt
                </h5>
                <p class="mb-0">
                    Website bán hàng thời trang sử dụng tiền tệ Việt Nam,
                    giao diện hiện đại, dễ mua hàng và dễ quản lý.
                </p>
            </div>

            <div class="col-lg-2 col-md-4">
                <h6 class="fw-bold text-dark mb-3">Danh mục</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('web.sanpham.index') }}" class="text-muted text-decoration-none">Sản phẩm</a>
                    <a href="#" class="text-muted text-decoration-none">Khuyến mãi</a>
                    <a href="#" class="text-muted text-decoration-none">Hàng mới</a>
                </div>
            </div>

            <div class="col-lg-3 col-md-4">
                <h6 class="fw-bold text-dark mb-3">Hỗ trợ khách hàng</h6>
                <div class="d-grid gap-2">
                    <a href="#" class="text-muted text-decoration-none">Theo dõi đơn hàng</a>
                    <a href="#" class="text-muted text-decoration-none">Chính sách vận chuyển</a>
                    <a href="#" class="text-muted text-decoration-none">Chính sách đổi trả</a>
                </div>
            </div>

            <div class="col-lg-3 col-md-4">
                <h6 class="fw-bold text-dark mb-3">Liên hệ</h6>
                <div class="d-grid gap-2">
                    <span><i class="bi bi-telephone me-2"></i>0901 234 567</span>
                    <span><i class="bi bi-envelope me-2"></i>hotro@banhangviet.vn</span>
                    <span><i class="bi bi-geo-alt me-2"></i>Quận Ninh Kiều, Cần Thơ</span>
                </div>
            </div>
        </div>

        <div class="border-top mt-4 pt-3 text-center">
            © {{ date('Y') }} Bán Hàng Việt. Thiết kế cho hệ thống bán hàng Việt Nam.
        </div>
    </div>
</footer>
