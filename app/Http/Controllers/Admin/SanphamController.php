<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SanphamRequest;
use App\Models\Danhmuc;
use App\Models\Sanpham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SanphamController extends Controller
{
    public function index(Request $request)
    {
        $tukhoa = $request->input('tu_khoa');
        $danhmucId = $request->input('danhmuc_id');
        $trangthai = $request->input('trang_thai');

        $danhsachdanhmuc = Danhmuc::query()
            ->where('trang_thai', true)
            ->orderBy('thu_tu')
            ->orderByDesc('id')
            ->get();

        $danhsachsanpham = Sanpham::query()
            ->with('danhmuc')
            ->when($tukhoa, function ($query) use ($tukhoa) {
                $query->where(function ($q) use ($tukhoa) {
                    $q->where('ten_san_pham', 'like', '%' . $tukhoa . '%')
                        ->orWhere('ma_san_pham', 'like', '%' . $tukhoa . '%')
                        ->orWhere('duong_dan', 'like', '%' . $tukhoa . '%');
                });
            })
            ->when($danhmucId, function ($query) use ($danhmucId) {
                $query->where('danhmuc_id', $danhmucId);
            })
            ->when($trangthai !== null && $trangthai !== '', function ($query) use ($trangthai) {
                $query->where('trang_thai', (bool) $trangthai);
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.sanpham.index', compact(
            'danhsachsanpham',
            'danhsachdanhmuc',
            'tukhoa',
            'danhmucId',
            'trangthai'
        ));
    }

    public function store(SanphamRequest $request)
    {
        $dulieu = $request->validated();

        $dulieu['duong_dan'] = $this->taoDuongDan(
            $dulieu['duong_dan'] ?? $dulieu['ten_san_pham']
        );

        $dulieu['ma_san_pham'] = $dulieu['ma_san_pham'] ?: $this->taoMaSanPham();
        $dulieu['muc_canh_bao_ton'] = $dulieu['muc_canh_bao_ton'] ?? 5;
        $dulieu['trang_thai'] = $request->has('trang_thai');
        $dulieu['noi_bat'] = $request->has('noi_bat');

        if ($request->hasFile('anh_dai_dien')) {
            $dulieu['anh_dai_dien'] = $request->file('anh_dai_dien')->store('sanpham', 'public');
        }

        Sanpham::create($dulieu);

        return redirect()
            ->route('admin.sanpham.index')
            ->with('thanhcong', 'Thêm sản phẩm thành công.');
    }

    public function update(SanphamRequest $request, Sanpham $sanpham)
    {
        $dulieu = $request->validated();

        $dulieu['duong_dan'] = $this->taoDuongDan(
            $dulieu['duong_dan'] ?? $dulieu['ten_san_pham']
        );

        $dulieu['ma_san_pham'] = $dulieu['ma_san_pham'] ?: $sanpham->ma_san_pham;
        $dulieu['muc_canh_bao_ton'] = $dulieu['muc_canh_bao_ton'] ?? 5;
        $dulieu['trang_thai'] = $request->has('trang_thai');
        $dulieu['noi_bat'] = $request->has('noi_bat');

        if ($request->hasFile('anh_dai_dien')) {
            if ($sanpham->anh_dai_dien) {
                Storage::disk('public')->delete($sanpham->anh_dai_dien);
            }

            $dulieu['anh_dai_dien'] = $request->file('anh_dai_dien')->store('sanpham', 'public');
        }

        $sanpham->update($dulieu);

        return redirect()
            ->route('admin.sanpham.index')
            ->with('thanhcong', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Sanpham $sanpham)
    {
        $sanpham->delete();

        return redirect()
            ->route('admin.sanpham.index')
            ->with('thanhcong', 'Xóa sản phẩm thành công.');
    }

    public function doitrangthai(Sanpham $sanpham)
    {
        $sanpham->update([
            'trang_thai' => !$sanpham->trang_thai,
        ]);

        return redirect()
            ->route('admin.sanpham.index')
            ->with('thanhcong', 'Cập nhật trạng thái sản phẩm thành công.');
    }

    private function taoDuongDan(string $chuoi): string
    {
        $duongdan = Str::slug($chuoi);
        $duongdanGoc = $duongdan;
        $dem = 1;

        while (Sanpham::where('duong_dan', $duongdan)->exists()) {
            $duongdan = $duongdanGoc . '-' . $dem;
            $dem++;
        }

        return $duongdan;
    }

    private function taoMaSanPham(): string
    {
        $so = Sanpham::withTrashed()->count() + 1;

        return 'SP' . str_pad($so, 5, '0', STR_PAD_LEFT);
    }
}
