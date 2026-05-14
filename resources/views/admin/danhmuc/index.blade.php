@extends('admin.layouts.app')

@section('tieude', 'Quản lý danh mục')

@section('noidung')
    <div class="page-title">
        <h1>Quản lý danh mục</h1>
        <p>Quản lý nhóm sản phẩm hiển thị trên website bán hàng.</p>
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

    <div class="bo-loc">
        <form action="{{ route('admin.danhmuc.index') }}" method="GET" class="bo-loc-form">
            <div class="bo-loc-input">
                <i class="bi bi-search"></i>
                <input
                    type="text"
                    name="tu_khoa"
                    value="{{ $tukhoa }}"
                    placeholder="Tìm kiếm theo tên danh mục, đường dẫn hoặc mô tả..."
                >
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-phu">
                    <i class="bi bi-search"></i>
                    Tìm kiếm
                </button>

                @if ($tukhoa)
                    <a href="{{ route('admin.danhmuc.index') }}" class="btn-phu">
                        <i class="bi bi-x-circle"></i>
                        Xóa lọc
                    </a>
                @endif

                <button type="button" class="btn-chinh" data-bs-toggle="modal" data-bs-target="#modalThemDanhMuc">
                    <i class="bi bi-plus-circle"></i>
                    Thêm danh mục
                </button>
            </div>
        </form>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h2>Danh sách danh mục</h2>
            <span class="text-muted small">
                Tổng: {{ $danhsachdanhmuc->total() }} danh mục
            </span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th>Tên danh mục</th>
                        <th>Đường dẫn</th>
                        <th>Mô tả</th>
                        <th style="width: 100px;">Thứ tự</th>
                        <th style="width: 130px;">Trạng thái</th>
                        <th style="width: 160px;">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($danhsachdanhmuc as $danhmuc)
                        <tr>
                            <td class="fw-semibold">#{{ $danhmuc->id }}</td>

                            <td>
                                <div class="fw-semibold">{{ $danhmuc->ten_danh_muc }}</div>
                                <div class="text-muted small">
                                    Tạo lúc {{ $danhmuc->created_at->format('d/m/Y H:i') }}
                                </div>
                            </td>

                            <td>
                                <span class="text-muted">/{{ $danhmuc->duong_dan }}</span>
                            </td>

                            <td>
                                <span class="text-muted">
                                    {{ $danhmuc->mo_ta ?: 'Chưa có mô tả' }}
                                </span>
                            </td>

                            <td>{{ $danhmuc->thu_tu }}</td>

                            <td>
                                @if ($danhmuc->trang_thai)
                                    <span class="badge-trang-thai badge-bat">Đang bật</span>
                                @else
                                    <span class="badge-trang-thai badge-tat">Đang tắt</span>
                                @endif
                            </td>

                            <td>
                                <div class="table-actions">
                                    <button
                                        type="button"
                                        class="btn-nho"
                                        title="Sửa"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalSuaDanhMuc{{ $danhmuc->id }}"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <form action="{{ route('admin.danhmuc.doitrangthai', $danhmuc) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn-nho"
                                            title="Đổi trạng thái"
                                        >
                                            @if ($danhmuc->trang_thai)
                                                <i class="bi bi-toggle-on"></i>
                                            @else
                                                <i class="bi bi-toggle-off"></i>
                                            @endif
                                        </button>
                                    </form>

                                    <form
                                        action="{{ route('admin.danhmuc.destroy', $danhmuc) }}"
                                        method="POST"
                                        data-confirm="Bạn có chắc muốn xóa danh mục này không?"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-nguyhiem" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-tags"></i>
                                    </div>
                                    <h5 class="fw-bold">Chưa có danh mục nào</h5>
                                    <p class="text-muted mb-3">
                                        Hãy tạo danh mục đầu tiên để bắt đầu quản lý sản phẩm.
                                    </p>
                                    <button type="button" class="btn-chinh" data-bs-toggle="modal" data-bs-target="#modalThemDanhMuc">
                                        <i class="bi bi-plus-circle"></i>
                                        Thêm danh mục
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($danhsachdanhmuc->hasPages())
            <div class="p-3 border-top">
                {{ $danhsachdanhmuc->links() }}
            </div>
        @endif
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
                            <label class="form-label">Đường dẫn</label>
                            <input type="text" name="duong_dan" class="form-control" value="{{ old('duong_dan', $danhmuc->duong_dan) }}">
                            <div class="form-text">Có thể để trống, hệ thống tự tạo từ tên danh mục.</div>
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
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="trang_thai"
                                value="1"
                                id="trangThaiSua{{ $danhmuc->id }}"
                                {{ $danhmuc->trang_thai ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="trangThaiSua{{ $danhmuc->id }}">
                                Bật hiển thị danh mục
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-phu" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn-chinh">
                            <i class="bi bi-save"></i>
                            Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="modalThemDanhMuc" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.danhmuc.store') }}" method="POST" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Thêm danh mục mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="ten_danh_muc"
                            class="form-control"
                            value="{{ old('ten_danh_muc') }}"
                            placeholder="Ví dụ: Áo thun nam"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Đường dẫn</label>
                        <input
                            type="text"
                            name="duong_dan"
                            class="form-control"
                            value="{{ old('duong_dan') }}"
                            placeholder="Ví dụ: ao-thun-nam"
                        >
                        <div class="form-text">Có thể để trống, hệ thống tự tạo từ tên danh mục.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea
                            name="mo_ta"
                            rows="3"
                            class="form-control"
                            placeholder="Mô tả ngắn về danh mục..."
                        >{{ old('mo_ta') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thứ tự hiển thị</label>
                        <input
                            type="number"
                            name="thu_tu"
                            class="form-control"
                            value="{{ old('thu_tu', 0) }}"
                            min="0"
                        >
                    </div>

                    <div class="form-check form-switch">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="trang_thai"
                            value="1"
                            id="trangThaiThem"
                            checked
                        >
                        <label class="form-check-label" for="trangThaiThem">
                            Bật hiển thị danh mục
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-phu" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn-chinh">
                        <i class="bi bi-plus-circle"></i>
                        Thêm danh mục
                    </button>
                </div>
            </form>
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
