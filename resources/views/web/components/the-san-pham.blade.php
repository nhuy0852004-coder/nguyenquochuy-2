<div class="product-card sanpham-card-bay">
    <a href="{{ route('web.sanpham.chitiet', $sanpham->duong_dan) }}" class="product-image-link">
        @if ($sanpham->anh_dai_dien)
            <img src="{{ asset('storage/' . $sanpham->anh_dai_dien) }}" alt="{{ $sanpham->ten_san_pham }}" class="anh-bay-gio">
        @else
            <div class="product-image-empty">
                <i class="bi bi-image"></i>
            </div>
        @endif

        @if ($sanpham->gia_khuyen_mai)
            <span class="product-sale-badge">Giảm giá</span>
        @endif
    </a>

    <div class="product-body">
        <div class="product-category">
            {{ $sanpham->danhmuc?->ten_danh_muc ?? 'Sản phẩm' }}
        </div>

        <a href="{{ route('web.sanpham.chitiet', $sanpham->duong_dan) }}" class="product-name">
            {{ $sanpham->ten_san_pham }}
        </a>

        <div class="product-price">
            @if ($sanpham->gia_khuyen_mai)
                <span class="price-sale">
                    {{ number_format($sanpham->gia_khuyen_mai, 0, ',', '.') }} ₫
                </span>
                <span class="price-old">
                    {{ number_format($sanpham->gia_ban, 0, ',', '.') }} ₫
                </span>
            @else
                <span class="price-normal">
                    {{ number_format($sanpham->gia_ban, 0, ',', '.') }} ₫
                </span>
            @endif
        </div>

        <div class="product-actions">
            @if ($sanpham->so_luong_ton > 0)
                <form action="{{ route('web.giohang.them', $sanpham) }}" method="POST" class="flex-fill form-them-gio">
                    @csrf
                    <input type="hidden" name="so_luong" value="1">

                    <button type="submit" class="btn-add-cart w-100">
                        <i class="bi bi-bag-plus me-1"></i>
                        Thêm giỏ
                    </button>
                </form>
            @else
                <button type="button" class="btn-add-cart" disabled>
                    Hết hàng
                </button>
            @endif

            <a href="{{ route('web.sanpham.chitiet', $sanpham->duong_dan) }}" class="btn-view-product">
                <i class="bi bi-eye"></i>
            </a>
        </div>
    </div>
</div>
