@extends('admin.layouts.app')

@section('tieude', 'Quản lý người dùng')

@section('noidung')
    <div class="page-title">
        <h1>Quản lý người dùng</h1>
        <p>Quản lý tài khoản quản trị, nhân viên và trạng thái truy cập hệ thống.</p>
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
        <form action="{{ route('admin.nguoidung.index') }}" method="GET" class="user-filter-grid">
            <div class="bo-loc-input">
                <i class="bi bi-search"></i>
                <input
                    type="text"
                    name="tu_khoa"
                    value="{{ $tukhoa }}"
                    placeholder="Tìm theo họ tên, email hoặc vai trò..."
                >
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-phu">
                    <i class="bi bi-search"></i>
                    Tìm kiếm
                </button>

                @if ($tukhoa)
                    <a href="{{ route('admin.nguoidung.index') }}" class="btn-phu">
                        Xóa lọc
                    </a>
                @endif

                <button type="button" class="btn-chinh" data-bs-toggle="modal" data-bs-target="#modalThemNguoiDung">
                    <i class="bi bi-plus-circle"></i>
                    Thêm người dùng
                </button>
            </div>
        </form>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h2>Danh sách người dùng</h2>
            <span class="text-muted small">
                Tổng: {{ $danhsachnguoidung->total() }} tài khoản
            </span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Người dùng</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th style="width: 190px;">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($danhsachnguoidung as $nguoidung)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar">
                                        {{ mb_substr($nguoidung->ho_ten, 0, 1) }}
                                    </div>

                                    <div>
                                        <div class="fw-bold">
                                            {{ $nguoidung->ho_ten }}

                                            @if (auth()->id() === $nguoidung->id)
                                                <span class="badge-trang-thai badge-xacnhan ms-1">Bạn</span>
                                            @endif
                                        </div>

                                        <div class="text-muted small">
                                            ID: #{{ $nguoidung->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>{{ $nguoidung->email }}</td>

                            <td>
                                @if ($nguoidung->vai_tro === 'admin')
                                    <span class="user-role-badge user-role-admin">Quản trị viên</span>
                                @else
                                    <span class="user-role-badge user-role-nhanvien">Nhân viên</span>
                                @endif
                            </td>

                            <td>
                                @if ($nguoidung->trang_thai)
                                    <span class="badge-trang-thai badge-bat">Đang hoạt động</span>
                                @else
                                    <span class="badge-trang-thai badge-huy">Đã khóa</span>
                                @endif
                            </td>

                            <td class="text-muted">
                                {{ $nguoidung->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                <div class="table-actions">
                                    <button
                                        type="button"
                                        class="btn-nho"
                                        title="Sửa"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalSuaNguoiDung{{ $nguoidung->id }}"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="btn-nho"
                                        title="Đổi mật khẩu"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDoiMatKhau{{ $nguoidung->id }}"
                                    >
                                        <i class="bi bi-key"></i>
                                    </button>

                                    <form action="{{ route('admin.nguoidung.doitrangthai', $nguoidung) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn-nho"
                                            title="Khóa/mở tài khoản"
                                        >
                                            @if ($nguoidung->trang_thai)
                                                <i class="bi bi-lock"></i>
                                            @else
                                                <i class="bi bi-unlock"></i>
                                            @endif
                                        </button>
                                    </form>

                                    <form
                                        action="{{ route('admin.nguoidung.destroy', $nguoidung) }}"
                                        method="POST"
                                        data-confirm-title="Xóa người dùng?"
                                        data-confirm="Bạn có chắc muốn xóa tài khoản {{ $nguoidung->ho_ten }} không?"
                                        data-confirm-button="Xóa"
                                        data-confirm-icon="warning"
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
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-person-gear"></i>
                                    </div>
                                    <h5 class="fw-bold">Chưa có người dùng</h5>
                                    <p class="text-muted mb-3">
                                        Hãy tạo tài khoản quản trị đầu tiên cho hệ thống.
                                    </p>
                                    <button type="button" class="btn-chinh" data-bs-toggle="modal" data-bs-target="#modalThemNguoiDung">
                                        <i class="bi bi-plus-circle"></i>
                                        Thêm người dùng
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($danhsachnguoidung->hasPages())
            <div class="p-3 border-top">
                {{ $danhsachnguoidung->links() }}
            </div>
        @endif
    </div>

    @foreach ($danhsachnguoidung as $nguoidung)
        <div class="modal fade" id="modalSuaNguoiDung{{ $nguoidung->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('admin.nguoidung.update', $nguoidung) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Sửa người dùng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="ho_ten"
                                class="form-control"
                                value="{{ old('ho_ten', $nguoidung->ho_ten) }}"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email', $nguoidung->email) }}"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Vai trò</label>
                            <select name="vai_tro" class="form-select">
                                <option value="admin" {{ $nguoidung->vai_tro === 'admin' ? 'selected' : '' }}>
                                    Quản trị viên
                                </option>
                                <option value="nhan_vien" {{ $nguoidung->vai_tro === 'nhan_vien' ? 'selected' : '' }}>
                                    Nhân viên
                                </option>
                            </select>
                        </div>

                        <div class="form-check form-switch">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="trang_thai"
                                value="1"
                                id="trangThaiSua{{ $nguoidung->id }}"
                                {{ $nguoidung->trang_thai ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="trangThaiSua{{ $nguoidung->id }}">
                                Tài khoản hoạt động
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

        <div class="modal fade" id="modalDoiMatKhau{{ $nguoidung->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('admin.nguoidung.doimatkhau', $nguoidung) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PATCH')

                    <div class="modal-header">
                        <h5 class="modal-title">Đổi mật khẩu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Người dùng</label>
                            <input type="text" class="form-control" value="{{ $nguoidung->ho_ten }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                            <input
                                type="password"
                                name="mat_khau"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                            <input
                                type="password"
                                name="mat_khau_confirmation"
                                class="form-control"
                                required
                            >
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-phu" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn-chinh">
                            <i class="bi bi-key"></i>
                            Đổi mật khẩu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="modalThemNguoiDung" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.nguoidung.store') }}" method="POST" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Thêm người dùng mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="ho_ten"
                            class="form-control"
                            value="{{ old('ho_ten') }}"
                            placeholder="Ví dụ: Nguyễn Quốc Huy"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email') }}"
                            placeholder="admin@cuahang.vn"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Vai trò</label>
                        <select name="vai_tro" class="form-select">
                            <option value="admin">Quản trị viên</option>
                            <option value="nhan_vien">Nhân viên</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                        <input
                            type="password"
                            name="mat_khau"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                        <input
                            type="password"
                            name="mat_khau_confirmation"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="form-check form-switch">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="trang_thai"
                            value="1"
                            id="trangThaiThemNguoiDung"
                            checked
                        >
                        <label class="form-check-label" for="trangThaiThemNguoiDung">
                            Tài khoản hoạt động
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-phu" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn-chinh">
                        <i class="bi bi-plus-circle"></i>
                        Thêm người dùng
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
