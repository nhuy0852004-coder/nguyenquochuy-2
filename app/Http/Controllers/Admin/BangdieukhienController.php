<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BangdieukhienController extends Controller
{
    public function index()
    {
        $thongke = [
            'doanh_thu_hom_nay' => 0,
            'don_hang_hom_nay' => 0,
            'tong_san_pham' => 0,
            'tong_khach_hang' => 0,
        ];

        $donhangmoi = [
            [
                'ma_don_hang' => 'DH0001',
                'khach_hang' => 'Nguyễn Văn An',
                'so_dien_thoai' => '0901234567',
                'tong_tien' => 450000,
                'trang_thai' => 'Chờ xác nhận',
                'thoi_gian' => 'Hôm nay 09:30',
            ],
            [
                'ma_don_hang' => 'DH0002',
                'khach_hang' => 'Trần Thị Mai',
                'so_dien_thoai' => '0912345678',
                'tong_tien' => 780000,
                'trang_thai' => 'Đã xác nhận',
                'thoi_gian' => 'Hôm nay 10:15',
            ],
            [
                'ma_don_hang' => 'DH0003',
                'khach_hang' => 'Lê Quốc Huy',
                'so_dien_thoai' => '0987654321',
                'tong_tien' => 320000,
                'trang_thai' => 'Đang giao hàng',
                'thoi_gian' => 'Hôm nay 11:05',
            ],
        ];

        return view('admin.bangdieukhien.index', compact('thongke', 'donhangmoi'));
    }
}
