<?php

namespace App\Services;

use App\Models\Chitietdonhang;
use App\Models\Donhang;
use App\Repositories\DonhangRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BaocaoService
{
    public function __construct(
        private DonhangRepository $donhangRepository
    ) {
        //
    }

    public function taoBaoCao(Carbon $tuNgay, Carbon $denNgay): array
    {
        return [
            'thongke' => $this->layThongKeTongQuan($tuNgay, $denNgay),

            'doanhThuNhanh' => [
                'hom_nay' => $this->donhangRepository->tinhDoanhThuTheoKhoang(now()->startOfDay(), now()->endOfDay()),
                'tuan_nay' => $this->donhangRepository->tinhDoanhThuTheoKhoang(now()->startOfWeek(), now()->endOfWeek()),
                'thang_nay' => $this->donhangRepository->tinhDoanhThuTheoKhoang(now()->startOfMonth(), now()->endOfMonth()),
            ],

            'doanhThu7Ngay' => $this->layDoanhThu7NgayGanNhat(),

            'topSanPhamBanChay' => $this->layTopSanPhamBanChay($tuNgay, $denNgay),

            'danhsachdonhang' => $this->donhangRepository->layDonHangTrongKhoang($tuNgay, $denNgay, 20),
        ];
    }

    private function layThongKeTongQuan(Carbon $tuNgay, Carbon $denNgay): array
    {
        return [
            'tong_doanh_thu' => $this->donhangRepository->tinhDoanhThuTheoKhoang($tuNgay, $denNgay),

            'tong_don_hang' => $this->donhangRepository->demDonHangTheoKhoang($tuNgay, $denNgay),

            'tong_don_hop_le' => $this->donhangRepository->demDonHopLeTheoKhoang($tuNgay, $denNgay),

            'tong_don_huy' => $this->donhangRepository->demDonHuyTheoKhoang($tuNgay, $denNgay),

            'tong_san_pham_da_ban' => Chitietdonhang::query()
                ->whereHas('donhang', function ($query) use ($tuNgay, $denNgay) {
                    $query->whereBetween('created_at', [$tuNgay, $denNgay])
                        ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY);
                })
                ->sum('so_luong'),
        ];
    }

    private function layDoanhThu7NgayGanNhat(): array
    {
        $tuNgay = now()->subDays(6)->startOfDay();
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

        $ketqua = [];

        for ($i = 6; $i >= 0; $i--) {
            $ngay = now()->subDays($i);
            $key = $ngay->toDateString();

            $ketqua[] = [
                'ngay' => $ngay->format('d/m'),
                'ngay_day_du' => $ngay->format('d/m/Y'),
                'doanh_thu' => (int) ($dulieu[$key]->doanh_thu ?? 0),
                'so_don' => (int) ($dulieu[$key]->so_don ?? 0),
            ];
        }

        return $ketqua;
    }

    private function layTopSanPhamBanChay(Carbon $tuNgay, Carbon $denNgay)
    {
        return Chitietdonhang::query()
            ->select(
                'sanpham_id',
                'ten_san_pham',
                'ma_san_pham',
                DB::raw('SUM(so_luong) as tong_so_luong'),
                DB::raw('SUM(thanh_tien) as tong_doanh_thu')
            )
            ->whereHas('donhang', function ($query) use ($tuNgay, $denNgay) {
                $query->whereBetween('created_at', [$tuNgay, $denNgay])
                    ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY);
            })
            ->groupBy('sanpham_id', 'ten_san_pham', 'ma_san_pham')
            ->orderByDesc('tong_so_luong')
            ->limit(10)
            ->get();
    }
}
