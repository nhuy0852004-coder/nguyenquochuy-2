<?php

namespace App\Services;

use App\Models\Chitietdonhang;
use App\Models\Donhang;
use App\Models\Khachhang;
use App\Models\Sanpham;
use App\Repositories\DonhangRepository;
use App\Repositories\SanphamRepository;
use Illuminate\Support\Facades\DB;

class ThongkeService
{
    public function __construct(
        private DonhangRepository $donhangRepository,
        private SanphamRepository $sanphamRepository
    ) {
        //
    }

    public function layDuLieuDashboard(): array
    {
        return cache()->remember('dashboard_thong_ke', 60, function () {
            return [
                'tongquan' => $this->layThongKeTongQuan(),
                'doanhthu14ngay' => $this->layDoanhThu14NgayGanNhat(),
                'trangthaidonhang' => $this->layThongKeTrangThaiDonHang(),
                'topsanpham' => $this->layTopSanPhamBanChay(),
                'sanphamcanhbao' => $this->laySanPhamCanhBaoTon(),
                'donhangmoi' => $this->donhangRepository->layDonHangMoiNhat(8),
            ];
        });
    }

    private function layThongKeTongQuan(): array
    {
        $homNay = now();
        $dauTuan = now()->startOfWeek();
        $cuoiTuan = now()->endOfWeek();
        $dauThang = now()->startOfMonth();
        $cuoiThang = now()->endOfMonth();

        return [
            'doanh_thu_hom_nay' => $this->tinhDoanhThuTheoKhoang(
                $homNay->copy()->startOfDay(),
                $homNay->copy()->endOfDay()
            ),
            'doanh_thu_tuan_nay' => $this->tinhDoanhThuTheoKhoang($dauTuan, $cuoiTuan),
            'doanh_thu_thang_nay' => $this->tinhDoanhThuTheoKhoang($dauThang, $cuoiThang),
            'tong_doanh_thu' => (int) Donhang::query()
                ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY)
                ->sum('tong_tien'),
            'don_hang_hom_nay' => Donhang::query()
                ->whereDate('created_at', now()->toDateString())
                ->count(),
            'tong_don_hang' => Donhang::query()->count(),
            'don_cho_xac_nhan' => Donhang::query()->where('trang_thai_don_hang', Donhang::TRANG_THAI_CHO_XAC_NHAN)->count(),
            'don_da_xac_nhan' => Donhang::query()->where('trang_thai_don_hang', Donhang::TRANG_THAI_DA_XAC_NHAN)->count(),
            'don_dang_giao' => Donhang::query()->where('trang_thai_don_hang', Donhang::TRANG_THAI_DANG_GIAO_HANG)->count(),
            'don_hoan_thanh' => Donhang::query()->where('trang_thai_don_hang', Donhang::TRANG_THAI_HOAN_THANH)->count(),
            'don_da_huy' => Donhang::query()->where('trang_thai_don_hang', Donhang::TRANG_THAI_DA_HUY)->count(),
            'tong_san_pham' => Sanpham::query()->count(),
            'san_pham_gan_het' => Sanpham::query()->whereColumn('so_luong_ton', '<=', 'muc_canh_bao_ton')->where('so_luong_ton', '>', 0)->count(),
            'san_pham_het_hang' => Sanpham::query()->where('so_luong_ton', '<=', 0)->count(),
            'tong_khach_hang' => Khachhang::query()->count(),
        ];
    }

    private function layDoanhThu14NgayGanNhat(): array
    {
        $tuNgay = now()->subDays(13)->startOfDay();
        $denNgay = now()->endOfDay();

        $dulieu = Donhang::query()
            ->selectRaw('DATE(created_at) as ngay')
            ->selectRaw('SUM(tong_tien) as doanh_thu')
            ->selectRaw('COUNT(*) as so_don')
            ->whereBetween('created_at', [$tuNgay, $denNgay])
            ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY)
            ->groupByRaw('DATE(created_at)')
            ->get()
            ->keyBy('ngay');

        $labels = [];
        $doanhthu = [];
        $sodon = [];

        for ($i = 13; $i >= 0; $i--) {
            $ngay = now()->subDays($i);
            $key = $ngay->toDateString();

            $labels[] = $ngay->format('d/m');
            $doanhthu[] = (int) ($dulieu[$key]->doanh_thu ?? 0);
            $sodon[] = (int) ($dulieu[$key]->so_don ?? 0);
        }

        return [
            'labels' => $labels,
            'doanh_thu' => $doanhthu,
            'so_don' => $sodon,
        ];
    }

    private function layThongKeTrangThaiDonHang(): array
    {
        $thongke = Donhang::query()
            ->select('trang_thai_don_hang', DB::raw('COUNT(*) as tong'))
            ->groupBy('trang_thai_don_hang')
            ->pluck('tong', 'trang_thai_don_hang');

        return [
            'labels' => [
                'Chờ xác nhận',
                'Đã xác nhận',
                'Đang giao hàng',
                'Hoàn thành',
                'Đã hủy',
            ],
            'values' => [
                (int) ($thongke[Donhang::TRANG_THAI_CHO_XAC_NHAN] ?? 0),
                (int) ($thongke[Donhang::TRANG_THAI_DA_XAC_NHAN] ?? 0),
                (int) ($thongke[Donhang::TRANG_THAI_DANG_GIAO_HANG] ?? 0),
                (int) ($thongke[Donhang::TRANG_THAI_HOAN_THANH] ?? 0),
                (int) ($thongke[Donhang::TRANG_THAI_DA_HUY] ?? 0),
            ],
        ];
    }

    private function layTopSanPhamBanChay(int $limit = 5)
    {
        return Chitietdonhang::query()
            ->select(
                'sanpham_id',
                'ten_san_pham',
                'ma_san_pham',
                DB::raw('SUM(so_luong) as tong_so_luong'),
                DB::raw('SUM(thanh_tien) as tong_doanh_thu')
            )
            ->whereHas('donhang', function ($query) {
                $query->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY);
            })
            ->groupBy('sanpham_id', 'ten_san_pham', 'ma_san_pham')
            ->orderByDesc('tong_so_luong')
            ->limit($limit)
            ->get();
    }

    private function laySanPhamCanhBaoTon(int $limit = 8)
    {
        return Sanpham::query()
            ->select('id', 'ten_san_pham', 'ma_san_pham', 'so_luong_ton', 'muc_canh_bao_ton', 'anh_dai_dien')
            ->whereColumn('so_luong_ton', '<=', 'muc_canh_bao_ton')
            ->orderBy('so_luong_ton')
            ->limit($limit)
            ->get();
    }

    private function tinhDoanhThuTheoKhoang($tuNgay, $denNgay): int
    {
        return (int) Donhang::query()
            ->whereBetween('created_at', [$tuNgay, $denNgay])
            ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY)
            ->sum('tong_tien');
    }
}
