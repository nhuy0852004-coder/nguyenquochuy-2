<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chitietdonhang;
use App\Models\Donhang;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BaocaoController extends Controller
{
    public function index(Request $request)
    {
        $tuNgay = $request->input('tu_ngay')
            ? Carbon::parse($request->input('tu_ngay'))->startOfDay()
            : now()->startOfMonth();

        $denNgay = $request->input('den_ngay')
            ? Carbon::parse($request->input('den_ngay'))->endOfDay()
            : now()->endOfDay();

        if ($tuNgay->gt($denNgay)) {
            return redirect()
                ->route('admin.baocao.index')
                ->with('loi', 'Ngày bắt đầu không được lớn hơn ngày kết thúc.');
        }

        $queryDonHangTrongKhoang = Donhang::query()
            ->whereBetween('created_at', [$tuNgay, $denNgay]);

        $queryDonHangHopLe = Donhang::query()
            ->whereBetween('created_at', [$tuNgay, $denNgay])
            ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY);

        $thongke = [
            'tong_doanh_thu' => (clone $queryDonHangHopLe)->sum('tong_tien'),

            'tong_don_hang' => (clone $queryDonHangTrongKhoang)->count(),

            'tong_don_hop_le' => (clone $queryDonHangHopLe)->count(),

            'tong_don_huy' => (clone $queryDonHangTrongKhoang)
                ->where('trang_thai_don_hang', Donhang::TRANG_THAI_DA_HUY)
                ->count(),

            'tong_san_pham_da_ban' => Chitietdonhang::query()
                ->whereHas('donhang', function ($query) use ($tuNgay, $denNgay) {
                    $query->whereBetween('created_at', [$tuNgay, $denNgay])
                        ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY);
                })
                ->sum('so_luong'),
        ];

        $doanhThuNhanh = [
            'hom_nay' => $this->tinhDoanhThuTheoKhoang(now()->startOfDay(), now()->endOfDay()),

            'tuan_nay' => $this->tinhDoanhThuTheoKhoang(now()->startOfWeek(), now()->endOfWeek()),

            'thang_nay' => $this->tinhDoanhThuTheoKhoang(now()->startOfMonth(), now()->endOfMonth()),
        ];

        $doanhThu7Ngay = $this->layDoanhThu7NgayGanNhat();

        $topSanPhamBanChay = Chitietdonhang::query()
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

        $danhsachdonhang = Donhang::query()
            ->whereBetween('created_at', [$tuNgay, $denNgay])
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        return view('admin.baocao.index', compact(
            'tuNgay',
            'denNgay',
            'thongke',
            'doanhThuNhanh',
            'doanhThu7Ngay',
            'topSanPhamBanChay',
            'danhsachdonhang'
        ));
    }

    private function tinhDoanhThuTheoKhoang(Carbon $tuNgay, Carbon $denNgay): int
    {
        return (int) Donhang::query()
            ->whereBetween('created_at', [$tuNgay, $denNgay])
            ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY)
            ->sum('tong_tien');
    }

    private function layDoanhThu7NgayGanNhat(): array
    {
        $ketqua = [];

        for ($i = 6; $i >= 0; $i--) {
            $ngay = now()->subDays($i);

            $doanhthu = $this->tinhDoanhThuTheoKhoang(
                $ngay->copy()->startOfDay(),
                $ngay->copy()->endOfDay()
            );

            $sodon = Donhang::query()
                ->whereBetween('created_at', [
                    $ngay->copy()->startOfDay(),
                    $ngay->copy()->endOfDay(),
                ])
                ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY)
                ->count();

            $ketqua[] = [
                'ngay' => $ngay->format('d/m'),
                'ngay_day_du' => $ngay->format('d/m/Y'),
                'doanh_thu' => $doanhthu,
                'so_don' => $sodon,
            ];
        }

        return $ketqua;
    }
}
