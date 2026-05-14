@extends('admin.layouts.app')

@section('tieude', 'Quản lý thông báo')

@section('noidung')
    <div class="page-title">
        <h1>Quản lý thông báo</h1>
        <p>Theo dõi thông báo đơn hàng mới, cảnh báo tồn kho và các sự kiện quan trọng.</p>
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

    <div class="bo-loc">
        <form action="{{ route('admin.thongbao.index') }}" method="GET" class="order-filter-grid">
            <select name="trang_thai" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="chua_doc" {{ $trangthai === 'chua_doc' ? 'selected' : '' }}>Chưa đọc</option>
                <option value="da_doc" {{ $trangthai === 'da_doc' ? 'selected' : '' }}>Đã đọc</option>
            </select>

            <select name="loai" class="form-select">
                <option value="">Tất cả loại</option>
                <option value="don_hang" {{ $loai === 'don_hang' ? 'selected' : '' }}>Đơn hàng</option>
                <option value="ton_kho" {{ $loai === 'ton_kho' ? 'selected' : '' }}>Tồn kho</option>
                <option value="he_thong" {{ $loai === 'he_thong' ? 'selected' : '' }}>Hệ thống</option>
            </select>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-phu">
                    <i class="bi bi-funnel"></i>
                    Lọc
                </button>

                @if ($trangthai || $loai)
                    <a href="{{ route('admin.thongbao.index') }}" class="btn-phu">
                        Xóa lọc
                    </a>
                @endif

                @if ($soluongchuadoc > 0)
                    <button
                        type="submit"
                        form="formDanhDauTatCa"
                        class="btn-chinh"
                    >
                        <i class="bi bi-check2-all"></i>
                        Đọc tất cả
                    </button>
                @endif
            </div>
        </form>

        <form id="formDanhDauTatCa" action="{{ route('admin.thongbao.daudoc.tatca') }}" method="POST" class="d-none">
            @csrf
            @method('PATCH')
        </form>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h2>Danh sách thông báo</h2>
            <span class="text-muted small">
                {{ $soluongchuadoc }} thông báo chưa đọc
            </span>
        </div>

        <div class="p-3">
            @if ($danhsachthongbao->count())
                <div class="thongbao-list">
                    @foreach ($danhsachthongbao as $thongbao)
                        <div class="thongbao-card {{ $thongbao->da_doc ? '' : 'chua-doc' }}">
                            <div class="thongbao-icon {{ $thongbao->loaiClass() }}">
                                @if ($thongbao->loai === App\Models\Thongbao::LOAI_DON_HANG)
                                    <i class="bi bi-receipt"></i>
                                @elseif ($thongbao->loai === App\Models\Thongbao::LOAI_TON_KHO)
                                    <i class="bi bi-box-seam"></i>
                                @else
                                    <i class="bi bi-info-circle"></i>
                                @endif
                            </div>

                            <div>
                                <div class="thongbao-title">
                                    {{ $thongbao->tieu_de }}

                                    @if (!$thongbao->da_doc)
                                        <span class="badge-trang-thai badge-xacnhan ms-1">Mới</span>
                                    @endif
                                </div>

                                <div class="thongbao-content">
                                    {{ $thongbao->noi_dung }}
                                </div>

                                <div class="thongbao-meta">
                                    <span>{{ $thongbao->loaiText() }}</span>
                                    <span>•</span>
                                    <span>{{ $thongbao->created_at->format('d/m/Y H:i') }}</span>

                                    @if ($thongbao->da_doc && $thongbao->doc_luc)
                                        <span>•</span>
                                        <span>Đã đọc lúc {{ $thongbao->doc_luc->format('d/m/Y H:i') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="thongbao-actions">
                                @if (!$thongbao->da_doc || $thongbao->duong_dan)
                                    <form action="{{ route('admin.thongbao.dadoc', $thongbao) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="btn-nho" title="Xem / đánh dấu đã đọc">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </form>
                                @endif

                                @if (auth()->user()?->laAdmin())
                                    <form
                                        action="{{ route('admin.thongbao.xoa', $thongbao) }}"
                                        method="POST"
                                        data-confirm-title="Xóa thông báo?"
                                        data-confirm="Bạn có chắc muốn xóa thông báo này không?"
                                        data-confirm-button="Xóa"
                                        data-confirm-icon="warning"
                                    >
                                    @csrf
                                    @method('DELETE')

                                        <button type="submit" class="btn-nguyhiem" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($danhsachthongbao->hasPages())
                    <div class="mt-3">
                        {{ $danhsachthongbao->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-bell"></i>
                    </div>
                    <h5 class="fw-bold">Chưa có thông báo</h5>
                    <p class="text-muted mb-0">
                        Khi khách đặt hàng hoặc sản phẩm gần hết hàng, thông báo sẽ xuất hiện tại đây.
                    </p>
                </div>
            @endif
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
