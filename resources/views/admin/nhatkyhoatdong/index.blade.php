@extends('admin.layouts.app')

@section('tieude', 'Nhật ký hoạt động')

@section('noidung')
    <div class="page-title">
        <h1>Nhật ký hoạt động</h1>
        <p>Theo dõi các thao tác quan trọng của quản trị viên và nhân viên trong hệ thống.</p>
    </div>

    <div class="bo-loc">
        <form action="{{ route('admin.nhatkyhoatdong.index') }}" method="GET" class="log-filter-grid">
            <div>
                <label class="form-label">Người dùng</label>
                <select name="nguoidung_id" class="form-select">
                    <option value="">Tất cả người dùng</option>
                    @foreach ($danhsachnguoidung as $nguoidung)
                        <option value="{{ $nguoidung->id }}" {{ (string) $nguoidungId === (string) $nguoidung->id ? 'selected' : '' }}>
                            {{ $nguoidung->ho_ten }} - {{ $nguoidung->email }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Hành động</label>
                <select name="hanh_dong" class="form-select">
                    <option value="">Tất cả hành động</option>
                    @foreach ($danhsachhanhdong as $key => $label)
                        <option value="{{ $key }}" {{ $hanhdong === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Từ ngày</label>
                <input type="date" name="tu_ngay" class="form-control" value="{{ $tuNgay }}">
            </div>

            <div>
                <label class="form-label">Đến ngày</label>
                <input type="date" name="den_ngay" class="form-control" value="{{ $denNgay }}">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-chinh">
                    <i class="bi bi-funnel"></i>
                    Lọc
                </button>

                @if ($nguoidungId || $hanhdong || $tuNgay || $denNgay)
                    <a href="{{ route('admin.nhatkyhoatdong.index') }}" class="btn-phu">
                        Xóa lọc
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h2>Danh sách nhật ký</h2>
            <span class="text-muted small">
                Tổng: {{ $danhsachnhatky->total() }} hoạt động
            </span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Thời gian</th>
                        <th>Người thực hiện</th>
                        <th>Hành động</th>
                        <th>Nội dung</th>
                        <th>Đối tượng</th>
                        <th>IP</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($danhsachnhatky as $nhatky)
                        <tr>
                            <td class="text-muted">
                                {{ $nhatky->created_at->format('d/m/Y H:i:s') }}
                            </td>

                            <td>
                                @if ($nhatky->nguoidung)
                                    <div class="log-user-cell">
                                        <div class="log-user-avatar">
                                            {{ mb_substr($nhatky->nguoidung->ho_ten, 0, 1) }}
                                        </div>

                                        <div>
                                            <div class="fw-bold">{{ $nhatky->nguoidung->ho_ten }}</div>
                                            <div class="text-muted small">{{ $nhatky->nguoidung->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Hệ thống</span>
                                @endif
                            </td>

                            <td>
                                <span class="log-action-badge {{ $nhatky->hanhDongClass() }}">
                                    {{ $nhatky->hanhDongText() }}
                                </span>
                            </td>

                            <td>
                                <div class="log-content-title">
                                    {{ $nhatky->tieu_de }}
                                </div>

                                <div class="log-content-desc">
                                    {{ $nhatky->noi_dung ?: 'Không có nội dung chi tiết' }}
                                </div>
                            </td>

                            <td>
                                @if ($nhatky->doi_tuong)
                                    <div class="fw-semibold">{{ $nhatky->doi_tuong }}</div>
                                    <div class="text-muted small">ID: {{ $nhatky->doi_tuong_id }}</div>
                                @else
                                    <span class="text-muted">Không có</span>
                                @endif
                            </td>

                            <td class="text-muted">
                                {{ $nhatky->dia_chi_ip ?: 'Không rõ' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <h5 class="fw-bold">Chưa có nhật ký hoạt động</h5>
                                    <p class="text-muted mb-0">
                                        Các thao tác quan trọng của Admin sẽ được ghi lại tại đây.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($danhsachnhatky->hasPages())
            <div class="p-3 border-top">
                {{ $danhsachnhatky->links() }}
            </div>
        @endif
    </div>
@endsection
