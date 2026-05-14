<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Danhmuc;
use App\Models\Sanpham;

class TrangchuController extends Controller
{
    public function index()
    {
        $danhsachdanhmuc = Danhmuc::query()
            ->where('trang_thai', true)
            ->orderBy('thu_tu')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        $sanphamnoibat = Sanpham::query()
            ->with('danhmuc')
            ->where('trang_thai', true)
            ->where('noi_bat', true)
            ->where('so_luong_ton', '>', 0)
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        $sanphammoi = Sanpham::query()
            ->with('danhmuc')
            ->where('trang_thai', true)
            ->where('so_luong_ton', '>', 0)
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        $sanphamkhuyenmai = Sanpham::query()
            ->with('danhmuc')
            ->where('trang_thai', true)
            ->whereNotNull('gia_khuyen_mai')
            ->where('gia_khuyen_mai', '>', 0)
            ->where('so_luong_ton', '>', 0)
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        $sanphambanchay = Sanpham::query()
            ->with('danhmuc')
            ->where('trang_thai', true)
            ->where('so_luong_ton', '>', 0)
            ->withSum(['chitietdonhang as tong_da_ban' => function ($query) {
                $query->whereHas('donhang', function ($q) {
                    $q->where('trang_thai_don_hang', '!=', 'da_huy');
                });
            }], 'so_luong')
            ->orderByDesc('tong_da_ban')
            ->limit(8)
            ->get();

        return view('web.trangchu.index', compact(
            'danhsachdanhmuc',
            'sanphamnoibat',
            'sanphammoi',
            'sanphamkhuyenmai',
            'sanphambanchay'
        ));
    }
}
