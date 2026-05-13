<footer class="web-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5 class="fw-bold text-dark mb-3">
                    @if (!empty($caidatcuahang?->logo))
                        <img
                            src="{{ asset('storage/' . $caidatcuahang->logo) }}"
                            alt="{{ $caidatcuahang->ten_cua_hang }}"
                            style="height:32px; width:auto; margin-right:6px;"
                        >
                    @else
                        <i class="bi bi-shop me-1 text-primary"></i>
                    @endif

                    {{ $caidatcuahang->ten_cua_hang ?? 'Bán Hàng Việt' }}
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
                    <a href="{{ route('web.sanpham.index', ['sap_xep' => 'gia_thap']) }}" class="text-muted text-decoration-none">Khuyến mãi</a>
                    <a href="{{ route('web.sanpham.index') }}" class="text-muted text-decoration-none">Hàng mới</a>
                </div>
            </div>

            <div class="col-lg-3 col-md-4">
                <h6 class="fw-bold text-dark mb-3">Hỗ trợ khách hàng</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('web.theodoi.index') }}" class="text-muted text-decoration-none">Theo dõi đơn hàng</a>
                    <a href="#" class="text-muted text-decoration-none">Chính sách vận chuyển</a>
                    <a href="#" class="text-muted text-decoration-none">Chính sách đổi trả</a>
                </div>
            </div>

            <div class="col-lg-3 col-md-4">
                <h6 class="fw-bold text-dark mb-3">Liên hệ</h6>
                <div class="d-grid gap-2">
                    @if (!empty($caidatcuahang?->so_dien_thoai))
                        <span><i class="bi bi-telephone me-2"></i>{{ $caidatcuahang->so_dien_thoai }}</span>
                    @endif

                    @if (!empty($caidatcuahang?->email))
                        <span><i class="bi bi-envelope me-2"></i>{{ $caidatcuahang->email }}</span>
                    @endif

                    @if (!empty($caidatcuahang?->dia_chi))
                        <span><i class="bi bi-geo-alt me-2"></i>{{ $caidatcuahang->dia_chi }}</span>
                    @endif

                    @if (!empty($caidatcuahang?->facebook))
                        <a href="{{ $caidatcuahang->facebook }}" target="_blank" class="text-muted text-decoration-none">
                            <i class="bi bi-facebook me-2"></i>Facebook
                        </a>
                    @endif

                    @if (!empty($caidatcuahang?->zalo))
                        <span><i class="bi bi-chat-dots me-2"></i>Zalo: {{ $caidatcuahang->zalo }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="border-top mt-4 pt-3 text-center">
            © {{ date('Y') }} {{ $caidatcuahang->ten_cua_hang ?? 'Bán Hàng Việt' }}. Thiết kế cho hệ thống bán hàng Việt Nam.
        </div>
    </div>
</footer>
