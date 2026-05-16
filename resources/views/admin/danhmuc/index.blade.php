@extends('admin.layouts.app')

@section('tieude', 'Quản lý danh mục')

@section('noidung')
    <div class="category-page-header">
        <div class="category-page-heading">
            <h1>Quản lý danh mục</h1>
            <p>Quản lý danh mục cha/con, cấu trúc hiển thị và nhóm sản phẩm trên website.</p>
        </div>

        <div class="category-page-actions">
            <button type="button" class="btn-chinh" data-bs-toggle="modal" data-bs-target="#modalThemDanhMuc">
                <i class="bi bi-plus-circle"></i>
                Thêm danh mục
            </button>
        </div>
    </div>

    @if (session('thanhcong'))
        <div class="toast-thongbao" id="toastThongBao">
            <i class="bi bi-check-circle-fill"></i>
            <div>
                <div class="fw-bold">Thành công</div>
                <div class="text-muted">{{ session('thanhcong') }}</div>
            </div>
        </div>
    @endif

    @if (session('loi'))
        <div class="alert alert-danger border-0 rounded-3">
            {{ session('loi') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-3">
            <div class="fw-bold mb-1">Vui lòng kiểm tra lại thông tin</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $loi)
                    <li>{{ $loi }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.danhmuc.index') }}"
        method="GET"
        class="category-filter-panel"
        id="formLocDanhMuc"
        data-skip-loading="true"
    >
        <input type="hidden" name="cot_sap_xep" id="cotSapXepDanhMuc" value="{{ $cotSapXep ?? 'thu_tu' }}">
        <input type="hidden" name="huong_sap_xep" id="huongSapXepDanhMuc" value="{{ $huongSapXep ?? 'asc' }}">

        <div class="category-filter-header">
            <div class="category-filter-title">
                <i class="bi bi-funnel"></i>
                Bộ lọc danh mục
            </div>

            <div class="category-filter-hint">
                Tìm nhanh, lọc đúng và sắp xếp danh mục theo nhu cầu quản trị.
            </div>
        </div>

        <div class="category-filter-body">
            <div class="category-filter-item category-filter-search">
                <label>Tìm kiếm</label>
                <div class="category-filter-search-box">
                    <i class="bi bi-search"></i>
                    <input
                        type="text"
                        name="tu_khoa"
                        value="{{ $tukhoa }}"
                        placeholder="Tìm theo tên, đường dẫn, mô tả..."
                    >
                </div>
            </div>

            <div class="category-filter-item">
                <label>Danh mục cha</label>
                <select name="parent_id" class="form-select auto-filter">
                    <option value="">Tất cả danh mục cha</option>
                    <option value="goc" {{ $parentId === 'goc' ? 'selected' : '' }}>Chỉ danh mục gốc</option>

                    @include('admin.danhmuc._option-cay', [
                        'danhsach' => $caydanhmuc,
                        'selected' => $parentId,
                        'cap' => 0,
                    ])
                </select>
            </div>

            <div class="category-filter-item">
                <label>Cấp danh mục</label>
                <select name="kieu_danh_muc" class="form-select auto-filter">
                    <option value="">Tất cả cấp</option>
                    <option value="goc" {{ $kieuDanhMuc === 'goc' ? 'selected' : '' }}>Danh mục gốc</option>
                    <option value="con" {{ $kieuDanhMuc === 'con' ? 'selected' : '' }}>Danh mục con</option>
                </select>
            </div>

            <div class="category-filter-item">
                <label>Trạng thái</label>
                <select name="trang_thai" class="form-select auto-filter">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1" {{ $trangthai === '1' ? 'selected' : '' }}>Đang bật</option>
                    <option value="0" {{ $trangthai === '0' ? 'selected' : '' }}>Đang tắt</option>
                </select>
            </div>

            <div class="category-filter-item">
                <label>Số lượng sản phẩm</label>
                <select name="so_luong_san_pham" class="form-select auto-filter">
                    <option value="">Tất cả số lượng</option>
                    <option value="khong_co" {{ $soLuongSanPham === 'khong_co' ? 'selected' : '' }}>Chưa có sản phẩm</option>
                    <option value="co_san_pham" {{ $soLuongSanPham === 'co_san_pham' ? 'selected' : '' }}>Có sản phẩm</option>
                    <option value="duoi_5" {{ $soLuongSanPham === 'duoi_5' ? 'selected' : '' }}>Dưới 5 sản phẩm</option>
                    <option value="tu_5_20" {{ $soLuongSanPham === 'tu_5_20' ? 'selected' : '' }}>Từ 5 - 20 sản phẩm</option>
                    <option value="tren_20" {{ $soLuongSanPham === 'tren_20' ? 'selected' : '' }}>Trên 20 sản phẩm</option>
                </select>
            </div>

            <div class="category-filter-item">
                <label>Sắp xếp</label>
                <select name="sap_xep" class="form-select auto-filter">
                    <option value="thu_tu" {{ $sapxep === 'thu_tu' ? 'selected' : '' }}>Theo thứ tự</option>
                    <option value="moi_tao" {{ $sapxep === 'moi_tao' ? 'selected' : '' }}>Mới tạo trước</option>
                    <option value="cu_nhat" {{ $sapxep === 'cu_nhat' ? 'selected' : '' }}>Cũ nhất trước</option>
                    <option value="nhieu_san_pham" {{ $sapxep === 'nhieu_san_pham' ? 'selected' : '' }}>Nhiều sản phẩm nhất</option>
                    <option value="nhieu_danh_muc_con" {{ $sapxep === 'nhieu_danh_muc_con' ? 'selected' : '' }}>Nhiều danh mục con nhất</option>
                    <option value="ten_az" {{ $sapxep === 'ten_az' ? 'selected' : '' }}>Tên A-Z</option>
                </select>
            </div>

            @if ($tukhoa || $parentId || $kieuDanhMuc || ($trangthai !== null && $trangthai !== '') || $soLuongSanPham || $sapxep !== 'thu_tu')
                <div class="category-filter-item category-filter-clear">
                    <label>&nbsp;</label>
                    <a href="{{ route('admin.danhmuc.index') }}" class="btn-phu">
                        <i class="bi bi-x-circle"></i>
                        Xóa lọc
                    </a>
                </div>
            @endif
        </div>
    </form>

    <div id="danhMucKetQuaWrap">
        @include('admin.danhmuc._ketqua')
    </div>

    @foreach ($danhsachdanhmuc as $danhmuc)
        <div class="modal fade" id="modalSuaDanhMuc{{ $danhmuc->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('admin.danhmuc.update', $danhmuc) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa danh mục</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" name="ten_danh_muc" class="form-control" value="{{ old('ten_danh_muc', $danhmuc->ten_danh_muc) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Danh mục cha</label>
                            <select name="parent_id" class="form-select">
                                <option value="">Không có - Danh mục gốc</option>
                                @include('admin.danhmuc._option-cay', ['danhsach' => $caydanhmuc, 'selected' => old('parent_id', $danhmuc->parent_id), 'cap' => 0, 'boquaId' => $danhmuc->id])
                            </select>
                            <div class="form-text">Không thể chọn chính nó hoặc danh mục con của nó làm cha.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Đường dẫn</label>
                            <input type="text" name="duong_dan" class="form-control" value="{{ old('duong_dan', $danhmuc->duong_dan) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="mo_ta" rows="3" class="form-control">{{ old('mo_ta', $danhmuc->mo_ta) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Thứ tự hiển thị</label>
                            <input type="number" name="thu_tu" class="form-control" value="{{ old('thu_tu', $danhmuc->thu_tu) }}" min="0">
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="trang_thai" value="1" id="trangThaiSua{{ $danhmuc->id }}" {{ $danhmuc->trang_thai ? 'checked' : '' }}>
                            <label class="form-check-label" for="trangThaiSua{{ $danhmuc->id }}">Bật hiển thị danh mục</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-phu" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn-chinh"><i class="bi bi-save"></i>Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="modalThemDanhMuc" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered category-modal-wide">
            <form action="{{ route('admin.danhmuc.store') }}" method="POST" class="modal-content category-modal">
                @csrf
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">Thêm danh mục mới</h5>
                        <div class="modal-subtitle">Tạo danh mục cha hoặc danh mục con để quản lý sản phẩm rõ ràng hơn.</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="category-form-grid">
                        <div class="category-form-main">
                            <div class="form-section-title"><i class="bi bi-info-circle"></i>Thông tin danh mục</div>
                            <div class="mb-3">
                                <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                                <input type="text" name="ten_danh_muc" class="form-control" value="{{ old('ten_danh_muc') }}" placeholder="Ví dụ: Áo thun nam" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Đường dẫn</label>
                                <input type="text" name="duong_dan" class="form-control" value="{{ old('duong_dan') }}" placeholder="Ví dụ: ao-thun-nam">
                                <div class="form-text">Có thể để trống, hệ thống sẽ tự tạo từ tên danh mục.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="mo_ta" rows="4" class="form-control" placeholder="Mô tả ngắn về danh mục...">{{ old('mo_ta') }}</textarea>
                            </div>
                        </div>
                        <div class="category-form-side">
                            <div class="form-section-title"><i class="bi bi-diagram-3"></i>Cấu trúc hiển thị</div>
                            <div class="mb-3">
                                <label class="form-label">Danh mục cha</label>
                                <select name="parent_id" class="form-select">
                                    <option value="">Không có - Danh mục gốc</option>
                                    @include('admin.danhmuc._option-cay', ['danhsach' => $caydanhmuc, 'selected' => old('parent_id'), 'cap' => 0])
                                </select>
                                <div class="form-text">Chọn danh mục cha nếu muốn tạo danh mục con.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Thứ tự hiển thị</label>
                                <input type="number" name="thu_tu" class="form-control" value="{{ old('thu_tu', 0) }}" min="0">
                                <div class="form-text">Số nhỏ hơn sẽ được ưu tiên hiển thị trước.</div>
                            </div>
                            <div class="category-status-box">
                                <div>
                                    <div class="fw-bold">Trạng thái hiển thị</div>
                                    <div class="text-muted small">Bật để danh mục có thể sử dụng trên website.</div>
                                </div>
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" name="trang_thai" value="1" id="trangThaiThem" checked>
                                </div>
                            </div>
                            <div class="category-help-box">
                                <div class="category-help-icon"><i class="bi bi-lightbulb"></i></div>
                                <div>
                                    <strong>Gợi ý</strong>
                                    <p>Ví dụ: “Thời trang nam” là danh mục cha, “Áo thun nam” là danh mục con.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-phu" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn-chinh"><i class="bi bi-plus-circle"></i>Thêm danh mục</button>
                </div>
            </form>
        </div>
    </div>
@endsection
