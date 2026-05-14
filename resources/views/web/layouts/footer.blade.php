<footer class="web-footer">
    <div class="container">
        <div class="web-footer-service">
            <div class="footer-service-item">
                <div class="footer-service-icon">
                    <i class="bi bi-truck"></i>
                </div>
                <div>
                    <strong>Giao hàng toàn quốc</strong>
                    <span>Nhận hàng tại nhà, thanh toán dễ dàng</span>
                </div>
            </div>

            <div class="footer-service-item">
                <div class="footer-service-icon">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div>
                    <strong>Đổi trả 7 ngày</strong>
                    <span>Hỗ trợ đổi trả nếu sản phẩm lỗi</span>
                </div>
            </div>

            <div class="footer-service-item">
                <div class="footer-service-icon">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div>
                    <strong>Thanh toán COD</strong>
                    <span>Thanh toán khi nhận hàng</span>
                </div>
            </div>

            <div class="footer-service-item">
                <div class="footer-service-icon">
                    <i class="bi bi-headset"></i>
                </div>
                <div>
                    <strong>Hỗ trợ nhanh</strong>
                    <span>Liên hệ qua hotline hoặc Zalo</span>
                </div>
            </div>
        </div>

        <div class="row g-4 web-footer-main">
            <div class="col-lg-4">
                <h5 class="web-footer-brand">
                    @if (!empty($caidatcuahang?->logo))
                        <img
                            src="{{ asset('storage/' . $caidatcuahang->logo) }}"
                            alt="{{ $caidatcuahang->ten_cua_hang }}"
                        >
                    @else
                        <i class="bi bi-shop text-primary"></i>
                    @endif

                    {{ $caidatcuahang->ten_cua_hang ?? 'Bán Hàng Việt' }}
                </h5>

                <p class="web-footer-desc">
                    Website bán hàng thời trang sử dụng tiền tệ Việt Nam,
                    giao diện hiện đại, dễ mua hàng và dễ theo dõi đơn.
                </p>

                <div class="web-footer-social">
                    @if (!empty($caidatcuahang?->facebook))
                        <a href="{{ $caidatcuahang->facebook }}" target="_blank">
                            <i class="bi bi-facebook"></i>
                        </a>
                    @endif

                    @if (!empty($caidatcuahang?->zalo))
                        <a href="#" title="Zalo: {{ $caidatcuahang->zalo }}">
                            <i class="bi bi-chat-dots"></i>
                        </a>
                    @endif
                </div>
            </div>

            <div class="col-lg-2 col-md-4">
                <h6 class="web-footer-title">Mua sắm</h6>

                <div class="web-footer-links">
                    <a href="{{ route('web.sanpham.index') }}">Tất cả sản phẩm</a>
                    <a href="{{ route('web.sanpham.index', ['sap_xep' => 'gia_thap']) }}">Sản phẩm giá tốt</a>
                    <a href="{{ route('web.sanpham.index') }}">Sản phẩm mới</a>
                    <a href="{{ route('web.giohang.index') }}">Giỏ hàng</a>
                </div>
            </div>

            <div class="col-lg-3 col-md-4">
                <h6 class="web-footer-title">Hỗ trợ khách hàng</h6>

                <div class="web-footer-links">
                    <a href="{{ route('web.theodoi.index') }}">Theo dõi đơn hàng</a>
                    <a href="#">Chính sách vận chuyển</a>
                    <a href="#">Chính sách đổi trả</a>
                    <a href="#">Hướng dẫn mua hàng</a>
                </div>
            </div>

            <div class="col-lg-3 col-md-4">
                <h6 class="web-footer-title">Liên hệ</h6>

                <div class="web-footer-contact">
                    @if (!empty($caidatcuahang?->so_dien_thoai))
                        <div>
                            <i class="bi bi-telephone"></i>
                            <span>{{ $caidatcuahang->so_dien_thoai }}</span>
                        </div>
                    @endif

                    @if (!empty($caidatcuahang?->email))
                        <div>
                            <i class="bi bi-envelope"></i>
                            <span>{{ $caidatcuahang->email }}</span>
                        </div>
                    @endif

                    @if (!empty($caidatcuahang?->dia_chi))
                        <div>
                            <i class="bi bi-geo-alt"></i>
                            <span>{{ $caidatcuahang->dia_chi }}</span>
                        </div>
                    @endif

                    @if (!empty($caidatcuahang?->zalo))
                        <div>
                            <i class="bi bi-chat-dots"></i>
                            <span>Zalo: {{ $caidatcuahang->zalo }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="web-footer-bottom">
            <div>
                © {{ date('Y') }} {{ $caidatcuahang->ten_cua_hang ?? 'Bán Hàng Việt' }}. Tất cả quyền được bảo lưu.
            </div>

            <div>
                Thiết kế cho hệ thống bán hàng Việt Nam
            </div>
        </div>
    </div>
</footer>
