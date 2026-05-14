@extends('admin.layouts.app')

@section('tieude', 'Quản lý sản phẩm')

@section('noidung')
    <div class="page-title">
        <h1>Quản lý sản phẩm</h1>
        <p>Quản lý sản phẩm, giá bán, tồn kho, hình ảnh và trạng thái hiển thị trên website.</p>
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
        <form action="{{ route('admin.sanpham.index') }}" method="GET" class="product-filter-grid">
            <div class="bo-loc-input">
                <i class="bi bi-search"></i>
                <input
                    type="text"
                    name="tu_khoa"
                    value="{{ $tukhoa }}"
                    placeholder="Tìm theo tên sản phẩm, mã sản phẩm, đường dẫn..."
                >
            </div>

            <select name="danhmuc_id" class="form-select">
                <option value="">Tất cả danh mục</option>
                @foreach ($danhsachdanhmuc as $danhmuc)
                    <option value="{{ $danhmuc->id }}" {{ (string) $danhmucId === (string) $danhmuc->id ? 'selected' : '' }}>
                        {{ $danhmuc->ten_danh_muc }}
                    </option>
                @endforeach
            </select>

            <select name="trang_thai" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="1" {{ $trangthai === '1' ? 'selected' : '' }}>Đang hiển thị</option>
                <option value="0" {{ $trangthai === '0' ? 'selected' : '' }}>Đang ẩn</option>
            </select>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-phu">
                    <i class="bi bi-funnel"></i>
                    Lọc
                </button>

                @if (auth()->user()?->laAdmin())
                    <button type="button" class="btn-chinh" data-bs-toggle="modal" data-bs-target="#modalThemSanPham">
                        <i class="bi bi-plus-circle"></i>
                        Thêm
                    </button>
                @endif
            </div>
        </form>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h2>Danh sách sản phẩm</h2>
            <span class="text-muted small">Tổng: {{ $danhsachsanpham->total() }} sản phẩm</span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="width: 76px;">Ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá bán</th>
                        <th style="width: 120px;">Tồn kho</th>
                        <th style="width: 130px;">Trạng thái</th>
                        <th style="width: 160px;">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($danhsachsanpham as $sanpham)
                        <tr>
                            <td>
                                @if ($sanpham->anh_dai_dien)
                                    <img src="{{ asset('storage/' . $sanpham->anh_dai_dien) }}" class="anh-san-pham" alt="{{ $sanpham->ten_san_pham }}">
                                @else
                                    <div class="anh-san-pham-rong">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </td>

                            <td class="product-name-cell">
                                <div class="fw-semibold">{{ $sanpham->ten_san_pham }}</div>
                                <div class="text-muted small">
                                    {{ $sanpham->ma_san_pham }} · /{{ $sanpham->duong_dan }}
                                </div>
                                @if ($sanpham->noi_bat)
                                    <span class="badge-trang-thai badge-xacnhan mt-1">Nổi bật</span>
                                @endif
                            </td>

                            <td>
                                <span class="text-muted">
                                    {{ $sanpham->danhmuc?->ten_danh_muc ?? 'Chưa có danh mục' }}
                                </span>
                            </td>

                            <td>
                                @if ($sanpham->gia_khuyen_mai)
                                    <div class="gia-khuyen-mai">{{ number_format($sanpham->gia_khuyen_mai, 0, ',', '.') }} ₫</div>
                                    <div class="gia-goc">{{ number_format($sanpham->gia_ban, 0, ',', '.') }} ₫</div>
                                @else
                                    <div class="gia-ban">{{ number_format($sanpham->gia_ban, 0, ',', '.') }} ₫</div>
                                @endif
                            </td>

                            <td>
                                <div class="fw-semibold">{{ $sanpham->so_luong_ton }}</div>

                                @if ($sanpham->hetHang())
                                    <span class="badge-trang-thai badge-hethang">Hết hàng</span>
                                @elseif ($sanpham->ganHetHang())
                                    <span class="badge-trang-thai badge-ganhet">Gần hết</span>
                                @else
                                    <span class="badge-trang-thai badge-conhang">Còn hàng</span>
                                @endif
                            </td>

                            <td>
                                @if ($sanpham->trang_thai)
                                    <span class="badge-trang-thai badge-bat">Đang hiển thị</span>
                                @else
                                    <span class="badge-trang-thai badge-tat">Đang ẩn</span>
                                @endif
                            </td>

                            <td>
                                @if (auth()->user()?->laAdmin())
                                    <div class="table-actions">
                                        <button
                                            type="button"
                                            class="btn-nho"
                                            title="Sửa"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalSuaSanPham{{ $sanpham->id }}"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <form action="{{ route('admin.sanpham.doitrangthai', $sanpham) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn-nho" title="Đổi trạng thái">
                                                @if ($sanpham->trang_thai)
                                                    <i class="bi bi-toggle-on"></i>
                                                @else
                                                    <i class="bi bi-toggle-off"></i>
                                                @endif
                                            </button>
                                        </form>

                                        <form
                                            action="{{ route('admin.sanpham.destroy', $sanpham) }}"
                                            method="POST"
                                            data-confirm="Bạn có chắc muốn xóa sản phẩm này không?"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn-nguyhiem" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small">Chỉ xem</span>
                                @endif
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <h5 class="fw-bold">Chưa có sản phẩm nào</h5>
                                    <p class="text-muted mb-3">
                                        Hãy thêm sản phẩm đầu tiên để bắt đầu bán hàng.
                                    </p>
                                    <button type="button" class="btn-chinh" data-bs-toggle="modal" data-bs-target="#modalThemSanPham">
                                        <i class="bi bi-plus-circle"></i>
                                        Thêm sản phẩm
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($danhsachsanpham->hasPages())
            <div class="p-3 border-top">
                {{ $danhsachsanpham->links() }}
            </div>
        @endif
    </div>

    @if (auth()->user()?->laAdmin())
        @foreach ($danhsachsanpham as $sanpham)
            <div class="modal fade" id="modalSuaSanPham{{ $sanpham->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <form action="{{ route('admin.sanpham.update', $sanpham) }}" method="POST" enctype="multipart/form-data" class="modal-content">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title">Sửa sản phẩm</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            @include('admin.sanpham._form', [
                                'sanpham' => $sanpham,
                                'danhsachdanhmuc' => $danhsachdanhmuc,
                                'prefix' => 'sua' . $sanpham->id
                            ])
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
    @endif

    @if (auth()->user()?->laAdmin())
        <div class="modal fade" id="modalThemSanPham" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <form action="{{ route('admin.sanpham.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Thêm sản phẩm mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        @include('admin.sanpham._form', [
                            'sanpham' => null,
                            'danhsachdanhmuc' => $danhsachdanhmuc,
                            'prefix' => 'them'
                        ])
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-phu" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn-chinh">
                            <i class="bi bi-plus-circle"></i>
                            Thêm sản phẩm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        const toastThongBao = document.getElementById('toastThongBao');

        if (toastThongBao) {
            setTimeout(() => {
                toastThongBao.style.display = 'none';
            }, 3000);
        }

        document.querySelectorAll('.input-tien').forEach(function (input) {
            input.addEventListener('input', function () {
                let giaTri = this.value.replace(/\D/g, '');

                if (!giaTri) {
                    this.value = '';
                    return;
                }

                this.value = new Intl.NumberFormat('vi-VN').format(giaTri);
            });
        });

        document.querySelectorAll('.input-anh').forEach(function (input) {
            input.addEventListener('change', function () {
                const previewId = this.dataset.preview;
                const preview = document.getElementById(previewId);

                if (this.files && this.files[0]) {
                    preview.src = URL.createObjectURL(this.files[0]);
                    preview.style.display = 'block';
                }
            });
        });
    </script>
@endpush