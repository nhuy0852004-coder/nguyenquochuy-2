<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donhang;
use App\Models\Khachhang;
use App\Models\Sanpham;

class BangdieukhienController extends Controller
{
    public function index()
    {
        $homNay = now()->toDateString();

        $thongke = [
            'doanh_thu_hom_nay' => Donhang::query()
                ->whereDate('created_at', $homNay)
                ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY)
                ->sum('tong_tien'),

            'don_hang_hom_nay' => Donhang::query()
                ->whereDate('created_at', $homNay)
                ->count(),

            'tong_san_pham' => Sanpham::query()->count(),

            'tong_khach_hang' => Khachhang::query()->count(),
        ];

        $donhangmoi = Donhang::query()
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $sanphamganhet = Sanpham::query()
            ->whereColumn('so_luong_ton', '<=', 'muc_canh_bao_ton')
            ->orderBy('so_luong_ton')
            ->limit(6)
            ->get();

        return view('admin.bangdieukhien.index', compact(
            'thongke',
            'donhangmoi',
            'sanphamganhet'
        ));
    }
}
