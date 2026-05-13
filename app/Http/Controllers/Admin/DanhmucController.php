<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DanhmucRequest;
use App\Models\Danhmuc;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DanhmucController extends Controller
{
    public function index(Request $request)
    {
        $tukhoa = $request->input('tu_khoa');

        $danhsachdanhmuc = Danhmuc::query()
            ->when($tukhoa, function ($query) use ($tukhoa) {
                $query->where('ten_danh_muc', 'like', '%' . $tukhoa . '%')
                    ->orWhere('duong_dan', 'like', '%' . $tukhoa . '%')
                    ->orWhere('mo_ta', 'like', '%' . $tukhoa . '%');
            })
            ->orderBy('thu_tu')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.danhmuc.index', compact('danhsachdanhmuc', 'tukhoa'));
    }

    public function store(DanhmucRequest $request)
    {
        $dulieu = $request->validated();

        $dulieu['duong_dan'] = $this->taoDuongDan(
            $dulieu['duong_dan'] ?? $dulieu['ten_danh_muc']
        );

        $dulieu['thu_tu'] = $dulieu['thu_tu'] ?? 0;
        $dulieu['trang_thai'] = $request->has('trang_thai');

        Danhmuc::create($dulieu);

        return redirect()
            ->route('admin.danhmuc.index')
            ->with('thanhcong', 'Thêm danh mục thành công.');
    }

    public function update(DanhmucRequest $request, Danhmuc $danhmuc)
    {
        $dulieu = $request->validated();

        $dulieu['duong_dan'] = $this->taoDuongDan(
            $dulieu['duong_dan'] ?? $dulieu['ten_danh_muc']
        );

        $dulieu['thu_tu'] = $dulieu['thu_tu'] ?? 0;
        $dulieu['trang_thai'] = $request->has('trang_thai');

        $danhmuc->update($dulieu);

        return redirect()
            ->route('admin.danhmuc.index')
            ->with('thanhcong', 'Cập nhật danh mục thành công.');
    }

    public function destroy(Danhmuc $danhmuc)
    {
        $danhmuc->delete();

        return redirect()
            ->route('admin.danhmuc.index')
            ->with('thanhcong', 'Xóa danh mục thành công.');
    }

    public function doitrangthai(Danhmuc $danhmuc)
    {
        $danhmuc->update([
            'trang_thai' => !$danhmuc->trang_thai,
        ]);

        return redirect()
            ->route('admin.danhmuc.index')
            ->with('thanhcong', 'Cập nhật trạng thái danh mục thành công.');
    }

    private function taoDuongDan(string $chuoi): string
    {
        return Str::slug($chuoi);
    }
}
