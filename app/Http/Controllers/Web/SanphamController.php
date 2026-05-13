<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Danhmuc;
use App\Models\Sanpham;
use Illuminate\Http\Request;

class SanphamController extends Controller
{
    public function index(Request $request)
    {
        $tukhoa = $request->input('tu_khoa');
        $danhmucSlug = $request->input('danh_muc');
        $sapxep = $request->input('sap_xep', 'moi_nhat');

        $danhsachdanhmuc = Danhmuc::query()
            ->where('trang_thai', true)
            ->orderBy('thu_tu')
            ->orderByDesc('id')
            ->get();

        $danhsachsanpham = Sanpham::query()
            ->with('danhmuc')
            ->where('trang_thai', true)
            ->when($tukhoa, function ($query) use ($tukhoa) {
                $query->where(function ($q) use ($tukhoa) {
                    $q->where('ten_san_pham', 'like', '%' . $tukhoa . '%')
                        ->orWhere('mo_ta_ngan', 'like', '%' . $tukhoa . '%')
                        ->orWhere('ma_san_pham', 'like', '%' . $tukhoa . '%');
                });
            })
            ->when($danhmucSlug, function ($query) use ($danhmucSlug) {
                $query->whereHas('danhmuc', function ($q) use ($danhmucSlug) {
                    $q->where('duong_dan', $danhmucSlug);
                });
            });

        if ($sapxep === 'gia_thap') {
            $danhsachsanpham->orderByRaw('COALESCE(gia_khuyen_mai, gia_ban) ASC');
        } elseif ($sapxep === 'gia_cao') {
            $danhsachsanpham->orderByRaw('COALESCE(gia_khuyen_mai, gia_ban) DESC');
        } else {
            $danhsachsanpham->orderByDesc('id');
        }

        $danhsachsanpham = $danhsachsanpham
            ->paginate(12)
            ->withQueryString();

        return view('web.sanpham.index', compact(
            'danhsachsanpham',
            'danhsachdanhmuc',
            'tukhoa',
            'danhmucSlug',
            'sapxep'
        ));
    }

    public function chitiet(string $duongdan)
    {
        $sanpham = Sanpham::query()
            ->with('danhmuc')
            ->where('duong_dan', $duongdan)
            ->where('trang_thai', true)
            ->firstOrFail();

        $sanphamlienquan = Sanpham::query()
            ->with('danhmuc')
            ->where('trang_thai', true)
            ->where('id', '!=', $sanpham->id)
            ->where('danhmuc_id', $sanpham->danhmuc_id)
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return view('web.sanpham.chitiet', compact('sanpham', 'sanphamlienquan'));
    }
}
