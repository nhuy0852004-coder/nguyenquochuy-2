@extends('admin.layouts.app')

@section('tieude', 'Cài đặt cửa hàng')

@section('noidung')
    <div class="page-title">
        <h1>Cài đặt cửa hàng</h1>
        <p>Quản lý thông tin hiển thị trên website bán hàng và thông tin liên hệ của cửa hàng.</p>
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

    <div class="setting-grid">
        <div class="setting-card">
            <h2>Thông tin cửa hàng</h2>

            <form action="{{ route('admin.caidatcuahang.capnhat') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="setting-form-grid">
                    <div class="full">
                        <label class="form-label">Tên cửa hàng <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="ten_cua_hang"
                            class="form-control"
                            value="{{ old('ten_cua_hang', $caidat->ten_cua_hang) }}"
                            required
                        >
                    </div>

                    <div>
                        <label class="form-label">Số điện thoại</label>
                        <input
                            type="text"
                            name="so_dien_thoai"
                            class="form-control"
                            value="{{ old('so_dien_thoai', $caidat->so_dien_thoai) }}"
                            placeholder="0901234567"
                        >
                    </div>

                    <div>
                        <label class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', $caidat->email) }}"
                            placeholder="hotro@cuahang.vn"
                        >
                    </div>

                    <div class="full">
                        <label class="form-label">Địa chỉ</label>
                        <input
                            type="text"
                            name="dia_chi"
                            class="form-control"
                            value="{{ old('dia_chi', $caidat->dia_chi) }}"
                            placeholder="Quận Ninh Kiều, Cần Thơ"
                        >
                    </div>

                    <div>
                        <label class="form-label">Facebook</label>
                        <input
                            type="url"
                            name="facebook"
                            class="form-control"
                            value="{{ old('facebook', $caidat->facebook) }}"
                            placeholder="https://facebook.com/..."
                        >
                    </div>

                    <div>
                        <label class="form-label">Zalo</label>
                        <input
                            type="text"
                            name="zalo"
                            class="form-control"
                            value="{{ old('zalo', $caidat->zalo) }}"
                            placeholder="Số Zalo hoặc link Zalo"
                        >
                    </div>

                    <div class="full">
                        <label class="form-label">Logo cửa hàng</label>
                        <input
                            type="file"
                            name="logo"
                            class="form-control"
                            accept="image/*"
                        >
                        <div class="form-text">Ảnh nên dùng PNG/JPG/WEBP, dung lượng dưới 2MB.</div>
                    </div>

                    <div class="full">
                        <label class="form-label">Chính sách vận chuyển</label>
                        <textarea
                            name="chinh_sach_van_chuyen"
                            rows="5"
                            class="form-control"
                        >{{ old('chinh_sach_van_chuyen', $caidat->chinh_sach_van_chuyen) }}</textarea>
                    </div>

                    <div class="full">
                        <label class="form-label">Chính sách đổi trả</label>
                        <textarea
                            name="chinh_sach_doi_tra"
                            rows="5"
                            class="form-control"
                        >{{ old('chinh_sach_doi_tra', $caidat->chinh_sach_doi_tra) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="submit" class="btn-chinh">
                        <i class="bi bi-save"></i>
                        Lưu cài đặt
                    </button>
                </div>
            </form>
        </div>

        <div>
            <div class="setting-card mb-3">
                <h2>Logo hiện tại</h2>

                <div class="logo-preview-box">
                    @if ($caidat->logo)
                        <img src="{{ asset('storage/' . $caidat->logo) }}" class="logo-preview" alt="{{ $caidat->ten_cua_hang }}">

                        <form
                            action="{{ route('admin.caidatcuahang.xoalogo') }}"
                            method="POST"
                            onsubmit="return confirm('Bạn có chắc muốn xóa logo cửa hàng không?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn-nguyhiem">
                                <i class="bi bi-trash"></i>
                                Xóa logo
                            </button>
                        </form>
                    @else
                        <div class="logo-empty">
                            <i class="bi bi-shop"></i>
                        </div>
                        <div class="text-muted">Chưa có logo</div>
                    @endif
                </div>
            </div>

            <div class="setting-card">
                <h2>Xem trước thông tin</h2>

                <div class="store-preview">
                    <div class="store-preview-head">
                        <div class="store-preview-logo">
                            @if ($caidat->logo)
                                <img src="{{ asset('storage/' . $caidat->logo) }}" alt="{{ $caidat->ten_cua_hang }}">
                            @else
                                <i class="bi bi-shop"></i>
                            @endif
                        </div>

                        <div>
                            <div class="fw-bold">{{ $caidat->ten_cua_hang }}</div>
                            <div class="text-muted small">Thông tin hiển thị trên website</div>
                        </div>
                    </div>

                    <div class="store-preview-body">
                        <div class="store-preview-line">
                            <i class="bi bi-telephone"></i>
                            <span>{{ $caidat->so_dien_thoai ?: 'Chưa có số điện thoại' }}</span>
                        </div>

                        <div class="store-preview-line">
                            <i class="bi bi-envelope"></i>
                            <span>{{ $caidat->email ?: 'Chưa có email' }}</span>
                        </div>

                        <div class="store-preview-line">
                            <i class="bi bi-geo-alt"></i>
                            <span>{{ $caidat->dia_chi ?: 'Chưa có địa chỉ' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const toastThongBao = document.getElementById('toastThongBao');

        if (toastThongBao) {
            setTimeout(() => {
                toastThongBao.style.display = 'none';
            }, 3000);
        }
    </script>
@endpush
