<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Danhmuc;
use App\Models\Sanpham;
use App\Repositories\SanphamRepository;
use Illuminate\Http\Request;

class SanphamController extends Controller
{
    public function __construct(
        private SanphamRepository $sanphamRepository
    ) {
        //
    }

    public function index(Request $request)
    {
        $tukhoa = $request->input('tu_khoa');
        $danhmucSlug = $request->input('danh_muc');
        $sapxep = $request->input('sap_xep', 'moi_nhat');
        $khoangGia = $request->input('khoang_gia');

        $chiConHang = $request->boolean('con_hang');
        $chiKhuyenMai = $request->boolean('khuyen_mai');
        $chiNoiBat = $request->boolean('noi_bat');

        $danhsachdanhmuc = Danhmuc::query()
            ->where('trang_thai', true)
            ->orderBy('thu_tu')
            ->orderByDesc('id')
            ->get();

        $danhsachsanpham = $this->sanphamRepository->timKiemSanPhamWebsite(
            $tukhoa,
            $danhmucSlug,
            $sapxep,
            $khoangGia,
            $chiConHang,
            $chiKhuyenMai,
            $chiNoiBat,
            12
        );

        return view('web.sanpham.index', compact(
            'danhsachsanpham',
            'danhsachdanhmuc',
            'tukhoa',
            'danhmucSlug',
            'sapxep',
            'khoangGia',
            'chiConHang',
            'chiKhuyenMai',
            'chiNoiBat'
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
