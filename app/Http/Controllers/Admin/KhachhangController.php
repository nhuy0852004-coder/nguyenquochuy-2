<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donhang;
use App\Models\Khachhang;
use Illuminate\Http\Request;

class KhachhangController extends Controller
{
    public function index(Request $request)
    {
        $tukhoa = $request->input('tu_khoa');

        $danhsachkhachhang = Khachhang::query()
            ->withCount([
                'donhang as tong_so_don',
                'donhang as tong_so_don_khong_huy' => function ($query) {
                    $query->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY);
                },
            ])
            ->withSum([
                'donhang as tong_tien_da_mua' => function ($query) {
                    $query->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY);
                },
            ], 'tong_tien')
            ->when($tukhoa, function ($query) use ($tukhoa) {
                $query->where(function ($q) use ($tukhoa) {
                    $q->where('ho_ten', 'like', '%' . $tukhoa . '%')
                        ->orWhere('so_dien_thoai', 'like', '%' . $tukhoa . '%')
                        ->orWhere('email', 'like', '%' . $tukhoa . '%')
                        ->orWhere('dia_chi', 'like', '%' . $tukhoa . '%');
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.khachhang.index', compact(
            'danhsachkhachhang',
            'tukhoa'
        ));
    }

    public function chitiet(Khachhang $khachhang)
    {
        $khachhang->load([
            'donhang' => function ($query) {
                $query->select(
                    'id',
                    'khachhang_id',
                    'ma_don_hang',
                    'tong_tien',
                    'trang_thai_don_hang',
                    'created_at'
                )->orderByDesc('id');
            },
            'donhang.chitietdonhang:id,donhang_id,ten_san_pham,so_luong',
        ]);

        $thongke = [
            'tong_so_don' => $khachhang->donhang->count(),

            'tong_so_don_khong_huy' => $khachhang->donhang
                ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY)
                ->count(),

            'tong_so_don_huy' => $khachhang->donhang
                ->where('trang_thai_don_hang', Donhang::TRANG_THAI_DA_HUY)
                ->count(),

            'tong_tien_da_mua' => $khachhang->donhang
                ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY)
                ->sum('tong_tien'),

            'lan_mua_gan_nhat' => $khachhang->donhang
                ->sortByDesc('created_at')
                ->first(),
        ];

        return view('admin.khachhang.chitiet', compact(
            'khachhang',
            'thongke'
        ));
    }
}
