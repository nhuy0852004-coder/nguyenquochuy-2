<?php

namespace App\Services;

use App\Models\Donhang;
use App\Models\Khachhang;
use App\Models\Sanpham;
use App\Repositories\DonhangRepository;
use App\Repositories\SanphamRepository;

class ThongkeService
{
    public function __construct(
        private DonhangRepository $donhangRepository,
        private SanphamRepository $sanphamRepository
    ) {
        //
    }

    public function layThongKeDashboard(): array
    {
        return [
            'doanh_thu_hom_nay' => $this->donhangRepository->tinhDoanhThuHomNay(),
            'don_hang_hom_nay' => $this->donhangRepository->demDonHangHomNay(),
            'tong_san_pham' => Sanpham::query()->count(),
            'tong_khach_hang' => Khachhang::query()->count(),
        ];
    }

    public function layDuLieuDashboard(): array
    {
        return cache()->remember('dashboard_thong_ke', 60, function () {
            return [
                'thongke' => $this->layThongKeDashboard(),
                'donhangmoi' => $this->donhangRepository->layDonHangMoiNhat(6),
                'sanphamganhet' => $this->sanphamRepository->laySanPhamGanHet(6),
            ];
        });
    }
}
