<div class="product-form-grid">
    <div>
        <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
        <input
            type="text"
            name="ten_san_pham"
            class="form-control"
            value="{{ old('ten_san_pham', $sanpham?->ten_san_pham) }}"
            placeholder="Ví dụ: Áo thun nam basic"
            required
        >
    </div>

    <div>
        <label class="form-label">Danh mục <span class="text-danger">*</span></label>
        <select name="danhmuc_id" class="form-select" required>
            <option value="">Chọn danh mục</option>
            @foreach ($danhsachdanhmuc as $danhmuc)
                <option
                    value="{{ $danhmuc->id }}"
                    {{ (string) old('danhmuc_id', $sanpham?->danhmuc_id) === (string) $danhmuc->id ? 'selected' : '' }}
                >
                    {{ $danhmuc->ten_danh_muc }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="form-label">Mã sản phẩm</label>
        <input
            type="text"
            name="ma_san_pham"
            class="form-control"
            value="{{ old('ma_san_pham', $sanpham?->ma_san_pham) }}"
            placeholder="Có thể để trống, hệ thống tự tạo"
        >
    </div>

    <div>
        <label class="form-label">Đường dẫn</label>
        <input
            type="text"
            name="duong_dan"
            class="form-control"
            value="{{ old('duong_dan', $sanpham?->duong_dan) }}"
            placeholder="Có thể để trống, hệ thống tự tạo"
        >
    </div>

    <div>
        <label class="form-label">Giá bán <span class="text-danger">*</span></label>
        <input
            type="text"
            name="gia_ban"
            class="form-control input-tien"
            value="{{ old('gia_ban', $sanpham ? number_format($sanpham->gia_ban, 0, ',', '.') : '') }}"
            placeholder="Ví dụ: 250.000"
            required
        >
        <div class="form-text">Nhập theo tiền Việt Nam, ví dụ: 250.000</div>
    </div>

    <div>
        <label class="form-label">Giá khuyến mãi</label>
        <input
            type="text"
            name="gia_khuyen_mai"
            class="form-control input-tien"
            value="{{ old('gia_khuyen_mai', $sanpham?->gia_khuyen_mai ? number_format($sanpham->gia_khuyen_mai, 0, ',', '.') : '') }}"
            placeholder="Ví dụ: 199.000"
        >
    </div>

    <div>
        <label class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
        <input
            type="number"
            name="so_luong_ton"
            class="form-control"
            value="{{ old('so_luong_ton', $sanpham?->so_luong_ton ?? 0) }}"
            min="0"
            required
        >
    </div>

    <div>
        <label class="form-label">Mức cảnh báo tồn</label>
        <input
            type="number"
            name="muc_canh_bao_ton"
            class="form-control"
            value="{{ old('muc_canh_bao_ton', $sanpham?->muc_canh_bao_ton ?? 5) }}"
            min="0"
        >
    </div>

    <div class="full">
        <label class="form-label">Ảnh đại diện</label>
        <input
            type="file"
            name="anh_dai_dien"
            class="form-control input-anh"
            accept="image/*"
            data-preview="previewAnh{{ $prefix }}"
        >

        @if ($sanpham?->anh_dai_dien)
            <img
                src="{{ asset('storage/' . $sanpham->anh_dai_dien) }}"
                class="preview-anh"
                id="previewAnh{{ $prefix }}"
                style="display:block;"
            >
        @else
            <img class="preview-anh" id="previewAnh{{ $prefix }}">
        @endif

        <div class="form-text">Ảnh nên dùng JPG, PNG hoặc WEBP, dung lượng dưới 2MB.</div>
    </div>

    <div class="full">
        <label class="form-label">Mô tả ngắn</label>
        <textarea
            name="mo_ta_ngan"
            rows="3"
            class="form-control"
            placeholder="Mô tả ngắn hiển thị ở danh sách sản phẩm..."
        >{{ old('mo_ta_ngan', $sanpham?->mo_ta_ngan) }}</textarea>
    </div>

    <div class="full">
        <label class="form-label">Mô tả chi tiết</label>
        <textarea
            name="mo_ta_chi_tiet"
            rows="5"
            class="form-control"
            placeholder="Mô tả chi tiết chất liệu, form dáng, hướng dẫn bảo quản..."
        >{{ old('mo_ta_chi_tiet', $sanpham?->mo_ta_chi_tiet) }}</textarea>
    </div>

    <div class="full d-flex gap-4">
        <div class="form-check form-switch">
            <input
                class="form-check-input"
                type="checkbox"
                name="trang_thai"
                value="1"
                id="trangThai{{ $prefix }}"
                {{ old('trang_thai', $sanpham?->trang_thai ?? true) ? 'checked' : '' }}
            >
            <label class="form-check-label" for="trangThai{{ $prefix }}">
                Hiển thị sản phẩm
            </label>
        </div>

        <div class="form-check form-switch">
            <input
                class="form-check-input"
                type="checkbox"
                name="noi_bat"
                value="1"
                id="noiBat{{ $prefix }}"
                {{ old('noi_bat', $sanpham?->noi_bat ?? false) ? 'checked' : '' }}
            >
            <label class="form-check-label" for="noiBat{{ $prefix }}">
                Sản phẩm nổi bật
            </label>
        </div>
    </div>
</div>