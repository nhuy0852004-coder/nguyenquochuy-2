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
                    <table class="table align-middle category-table-real">
                        <thead>
                            <tr>
                                <th style="min-width: 280px;">
                                    <button
                                        type="button"
                                        class="{{ $sortClass('ten_danh_muc') }}"
                                        data-sort-column="ten_danh_muc"
                                    >
                                        Tên danh mục
                                        {!! $sortIcon('ten_danh_muc') !!}
                                    </button>
                                </th>

                                <th style="min-width: 180px;">Danh mục cha</th>

                                <th style="min-width: 230px;">Cấu trúc</th>

                                <th style="width: 120px;">
                                    <button
                                        type="button"
                                        class="{{ $sortClass('sanpham_count') }}"
                                        data-sort-column="sanpham_count"
                                    >
                                        Sản phẩm
                                        {!! $sortIcon('sanpham_count') !!}
                                    </button>
                                </th>

                                <th style="width: 130px;">
                                    <button
                                        type="button"
                                        class="{{ $sortClass('trang_thai') }}"
                                        data-sort-column="trang_thai"
                                    >
                                        Trạng thái
                                        {!! $sortIcon('trang_thai') !!}
                                    </button>
                                </th>

                                <th style="width: 136px;">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($danhsachdanhmuc as $danhmuc)
                                <tr>
                                    <td>
                                        <div class="category-name-real">
                                            <div class="category-name-title">
                                                {{ $danhmuc->ten_danh_muc }}
                                            </div>

                                            <div class="category-name-meta">
                                                <span>#{{ $danhmuc->id }}</span>
                                                <span>/{{ $danhmuc->duong_dan }}</span>
                                            </div>

                                            @if ($danhmuc->mo_ta)
                                                <div class="category-name-desc">
                                                    {{ \Illuminate\Support\Str::limit($danhmuc->mo_ta, 90) }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        @if ($danhmuc->cha)
                                            <div class="category-parent-real">
                                                {{ $danhmuc->cha->ten_danh_muc }}
                                            </div>
                                        @else
                                            <div class="category-parent-real muted">
                                                Danh mục gốc
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="category-structure-real">
                                            <div class="category-breadcrumb-real">
                                                {{ $danhmuc->breadcrumb() }}
                                            </div>

                                            <div class="category-structure-meta">
                                                <span>
                                                    {{ $danhmuc->parent_id ? 'Danh mục con' : 'Danh mục gốc' }}
                                                </span>

                                                <span>Thứ tự: {{ $danhmuc->thu_tu }}</span>

                                                <span>{{ $danhmuc->con_count }} mục con</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <a
                                            href="{{ route('admin.sanpham.index', ['danhmuc_id' => $danhmuc->id]) }}"
                                            class="category-product-count-real"
                                        >
                                            {{ $danhmuc->sanpham_count }}
                                        </a>
                                    </td>

                                    <td>
                                        <form action="{{ route('admin.danhmuc.doitrangthai', $danhmuc) }}" method="POST" class="status-toggle-form">
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="status-switch {{ $danhmuc->trang_thai ? 'on' : 'off' }}"
                                                title="Đổi trạng thái"
                                                aria-label="Đổi trạng thái"
                                            >
                                                <span class="status-switch-track">
                                                    <span class="status-switch-thumb"></span>
                                                </span>
                                            </button>
                                        </form>
                                    </td>

                                    <td>
                                        <div class="category-actions-real">
                                            <button
                                                type="button"
                                                class="btn-nho"
                                                title="Sửa"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalSuaDanhMuc{{ $danhmuc->id }}"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </button>

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
                                    <td colspan="6">
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
