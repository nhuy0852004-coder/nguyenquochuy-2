<div class="product-card sanpham-card-bay">
    <a href="{{ route('web.sanpham.chitiet', $sanpham->duong_dan) }}" class="product-image-link">
        @if ($sanpham->anh_dai_dien)
            <img
                src="{{ asset('storage/' . $sanpham->anh_dai_dien) }}"
                alt="{{ $sanpham->ten_san_pham }}"
                class="anh-bay-gio"
                loading="lazy"
            >
        @else
            <div class="product-image-empty">
                <i class="bi bi-image"></i>
            </div>
        @endif

        <div class="product-badges">
            @if ($sanpham->gia_khuyen_mai)
                @php
                    $phanTramGiam = $sanpham->gia_ban > 0
                        ? round((($sanpham->gia_ban - $sanpham->gia_khuyen_mai) / $sanpham->gia_ban) * 100)
                        : 0;
                @endphp

                <span class="product-sale-badge">
                    -{{ $phanTramGiam }}%
                </span>
            @endif

            @if ($sanpham->noi_bat)
                <span class="product-featured-badge">
                    Nổi bật
                </span>
            @endif
        </div>
    </a>

    <div class="product-body">
        <div class="product-category">
            {{ $sanpham->danhmuc?->ten_danh_muc ?? 'Sản phẩm' }}
        </div>

        <a href="{{ route('web.sanpham.chitiet', $sanpham->duong_dan) }}" class="product-name">
            {{ $sanpham->ten_san_pham }}
        </a>

        @if (!empty($sanpham->mo_ta_ngan))
            <div class="product-short-desc">
                {{ Str::limit($sanpham->mo_ta_ngan, 70) }}
            </div>
        @endif

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

        <div class="product-stock {{ $sanpham->so_luong_ton > 0 ? 'stock-in' : 'stock-out' }}">
            @if ($sanpham->so_luong_ton > 0)
                <i class="bi bi-check-circle"></i>
                Còn {{ $sanpham->so_luong_ton }} sản phẩm
            @else
                <i class="bi bi-x-circle"></i>
                Hết hàng
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
                <button type="button" class="btn-add-cart w-100" disabled>
                    Hết hàng
                </button>
            @endif

            <a href="{{ route('web.sanpham.chitiet', $sanpham->duong_dan) }}" class="btn-view-product">
                <i class="bi bi-eye"></i>
            </a>
        </div>
    </div>
</div>
