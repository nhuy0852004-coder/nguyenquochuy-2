@php
    $cotSapXep = $cotSapXep ?? 'thu_tu';
    $huongSapXep = $huongSapXep ?? 'asc';

    $sortClass = function ($cot) use ($cotSapXep, $huongSapXep) {
        if ($cot !== $cotSapXep) {
            return 'sort-link';
        }

        return 'sort-link active ' . ($huongSapXep === 'asc' ? 'asc' : 'desc');
    };

    $sortIcon = function ($cot) use ($cotSapXep, $huongSapXep) {
        if ($cot !== $cotSapXep) {
            return '<i class="bi bi-arrow-down-up"></i>';
        }

        return $huongSapXep === 'asc'
            ? '<i class="bi bi-sort-up"></i>'
            : '<i class="bi bi-sort-down"></i>';
    };
@endphp

<div id="danhMucKetQua">
    <div class="category-stat-grid">
        <div class="category-stat-card">
            <div class="category-stat-icon primary">
                <i class="bi bi-tags"></i>
            </div>

            <div>
                <div class="category-stat-value">{{ $danhsachdanhmuc->total() }}</div>
                <div class="category-stat-label">Tổng danh mục</div>
            </div>
        </div>

        <div class="category-stat-card">
            <div class="category-stat-icon success">
                <i class="bi bi-diagram-3"></i>
            </div>

            <div>
                <div class="category-stat-value">{{ $danhsachdanhmuc->whereNull('parent_id')->count() }}</div>
                <div class="category-stat-label">Danh mục gốc trong trang</div>
            </div>
        </div>

        <div class="category-stat-card">
            <div class="category-stat-icon warning">
                <i class="bi bi-layers"></i>
            </div>

            <div>
                <div class="category-stat-value">{{ $danhsachdanhmuc->whereNotNull('parent_id')->count() }}</div>
                <div class="category-stat-label">Danh mục con trong trang</div>
            </div>
        </div>

        <div class="category-stat-card">
            <div class="category-stat-icon info">
                <i class="bi bi-box-seam"></i>
            </div>

            <div>
                <div class="category-stat-value">{{ $danhsachdanhmuc->sum('sanpham_count') }}</div>
                <div class="category-stat-label">Sản phẩm trong trang</div>
            </div>
        </div>
    </div>

    @if ($tukhoa || $parentId || $kieuDanhMuc || ($trangthai !== null && $trangthai !== '') || $soLuongSanPham || $sapxep !== 'thu_tu' || $cotSapXep !== 'thu_tu' || $huongSapXep !== 'asc')
        <div class="filter-active-box">
            <div class="filter-active-title">
                <i class="bi bi-funnel"></i>
                Bộ lọc đang áp dụng
            </div>

            <div class="filter-active-tags">
                @if ($tukhoa)
                    <span>Từ khóa: {{ $tukhoa }}</span>
                @endif

                @if ($parentId)
                    <span>
                        Danh mục cha:
                        @if ($parentId === 'goc')
                            Danh mục gốc
                        @else
                            {{ optional($tatcadanhmuc->firstWhere('id', (int) $parentId))->ten_danh_muc }}
                        @endif
                    </span>
                @endif

                @if ($kieuDanhMuc)
                    <span>
                        Cấp:
                        {{ $kieuDanhMuc === 'goc' ? 'Danh mục gốc' : 'Danh mục con' }}
                    </span>
                @endif

                @if ($trangthai !== null && $trangthai !== '')
                    <span>
                        Trạng thái:
                        {{ $trangthai === '1' ? 'Đang bật' : 'Đang tắt' }}
                    </span>
                @endif

                @if ($soLuongSanPham)
                    <span>Số lượng sản phẩm: Đã lọc</span>
                @endif

                @if ($sapxep && $sapxep !== 'thu_tu')
                    <span>Sắp xếp: {{ $sapxep }}</span>
                @endif
            </div>
        </div>
    @endif

    <div class="category-layout-grid">
        <div class="content-card">
            <div class="content-card-header">
                <h2>Cây danh mục</h2>
                <span class="text-muted small">Hiển thị cấp cha/con</span>
            </div>

            <div class="p-3">
                @if ($caydanhmuc->count())
                    @include('admin.danhmuc._cay', ['danhsach' => $caydanhmuc])
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <h5 class="fw-bold">Chưa có danh mục</h5>
                        <p class="text-muted mb-0">Hãy tạo danh mục đầu tiên.</p>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <div class="content-card">
                <div class="content-card-header">
                    <h2>Danh sách danh mục</h2>
                    <span class="text-muted small">
                        Tìm thấy: {{ $danhsachdanhmuc->total() }} danh mục
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 70px;">
                                    <button type="button" class="{{ $sortClass('id') }}" data-sort-column="id">
                                        ID
                                        {!! $sortIcon('id') !!}
                                    </button>
                                </th>

                                <th>
                                    <button type="button" class="{{ $sortClass('ten_danh_muc') }}" data-sort-column="ten_danh_muc">
                                        Danh mục
                                        {!! $sortIcon('ten_danh_muc') !!}
                                    </button>
                                </th>

                                <th>Danh mục cha</th>

                                <th>Breadcrumb</th>

                                <th style="width: 110px;">
                                    <button type="button" class="{{ $sortClass('sanpham_count') }}" data-sort-column="sanpham_count">
                                        Sản phẩm
                                        {!! $sortIcon('sanpham_count') !!}
                                    </button>
                                </th>

                                <th style="width: 120px;">
                                    <button type="button" class="{{ $sortClass('con_count') }}" data-sort-column="con_count">
                                        Danh mục con
                                        {!! $sortIcon('con_count') !!}
                                    </button>
                                </th>

                                <th style="width: 90px;">
                                    <button type="button" class="{{ $sortClass('thu_tu') }}" data-sort-column="thu_tu">
                                        Thứ tự
                                        {!! $sortIcon('thu_tu') !!}
                                    </button>
                                </th>

                                <th style="width: 130px;">
                                    <button type="button" class="{{ $sortClass('trang_thai') }}" data-sort-column="trang_thai">
                                        Trạng thái
                                        {!! $sortIcon('trang_thai') !!}
                                    </button>
                                </th>

                                <th style="width: 130px;">
                                    <button type="button" class="{{ $sortClass('created_at') }}" data-sort-column="created_at">
                                        Ngày tạo
                                        {!! $sortIcon('created_at') !!}
                                    </button>
                                </th>

                                <th style="width: 160px;">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($danhsachdanhmuc as $danhmuc)
                                <tr>
                                    <td class="fw-semibold">#{{ $danhmuc->id }}</td>

                                    <td>
                                        <div class="category-name-cell">
                                            <div class="category-level-icon {{ $danhmuc->parent_id ? 'child' : 'root' }}">
                                                @if ($danhmuc->parent_id)
                                                    <i class="bi bi-arrow-return-right"></i>
                                                @else
                                                    <i class="bi bi-folder"></i>
                                                @endif
                                            </div>

                                            <div>
                                                <div class="fw-bold">{{ $danhmuc->ten_danh_muc }}</div>

                                                <div class="text-muted small">
                                                    /{{ $danhmuc->duong_dan }}
                                                </div>

                                                @if ($danhmuc->mo_ta)
                                                    <div class="category-desc-small">
                                                        {{ \Illuminate\Support\Str::limit($danhmuc->mo_ta, 70) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if ($danhmuc->cha)
                                            <div class="category-parent-pill">
                                                <i class="bi bi-folder2-open"></i>
                                                {{ $danhmuc->cha->ten_danh_muc }}
                                            </div>
                                        @else
                                            <span class="category-root-pill">
                                                <i class="bi bi-diagram-3"></i>
                                                Danh mục gốc
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="category-breadcrumb">
                                            @foreach (explode(' / ', $danhmuc->breadcrumb()) as $index => $item)
                                                @if ($index > 0)
                                                    <i class="bi bi-chevron-right"></i>
                                                @endif

                                                <span>{{ $item }}</span>
                                            @endforeach
                                        </div>
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.sanpham.index', ['danhmuc_id' => $danhmuc->id]) }}" class="category-count-box">
                                            <strong>{{ $danhmuc->sanpham_count }}</strong>
                                            <span>sản phẩm</span>
                                        </a>
                                    </td>

                                    <td>
                                        <div class="category-count-box neutral">
                                            <strong>{{ $danhmuc->con_count }}</strong>
                                            <span>danh mục con</span>
                                        </div>
                                    </td>

                                    <td>{{ $danhmuc->thu_tu }}</td>

                                    <td>
                                        @if ($danhmuc->trang_thai)
                                            <span class="badge-trang-thai badge-bat">Đang bật</span>
                                        @else
                                            <span class="badge-trang-thai badge-tat">Đang tắt</span>
                                        @endif
                                    </td>

                                    <td class="text-muted">
                                        {{ $danhmuc->created_at->format('d/m/Y H:i') }}
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

                                                <button type="submit" class="btn-nho" title="Đổi trạng thái">
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
                                                data-confirm-title="Xóa danh mục?"
                                                data-confirm="Bạn có chắc muốn xóa danh mục {{ $danhmuc->ten_danh_muc }} không?"
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
                                    <td colspan="10">
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
                    <div class="p-3 border-top ajax-pagination">
                        {{ $danhsachdanhmuc->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
