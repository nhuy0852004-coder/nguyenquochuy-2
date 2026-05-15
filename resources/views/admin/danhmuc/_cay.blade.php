<ul class="category-tree-list">
    @foreach ($danhsach as $item)
        <li>
            <div class="category-tree-item">
                <div>
                    <div class="fw-bold">{{ $item->ten_danh_muc }}</div>
                    <div class="text-muted small">
                        {{ $item->con_count ?? $item->con->count() }} danh mục con ·
                        {{ $item->sanpham_count ?? 0 }} sản phẩm
                    </div>
                </div>

                @if ($item->trang_thai)
                    <span class="badge-trang-thai badge-bat">Bật</span>
                @else
                    <span class="badge-trang-thai badge-tat">Tắt</span>
                @endif
            </div>

            @if ($item->con && $item->con->count())
                @include('admin.danhmuc._cay', ['danhsach' => $item->con])
            @endif
        </li>
    @endforeach
</ul>
