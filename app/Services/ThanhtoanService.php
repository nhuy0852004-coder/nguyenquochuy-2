<?php

namespace App\Services;

use App\Events\DonhangMoiEvent;
use App\Models\Chitietdonhang;
use App\Models\Donhang;
use App\Models\Khachhang;
use App\Models\Sanpham;
use Illuminate\Support\Facades\DB;

class ThanhtoanService
{
    public function __construct(
        private GiohangService $giohangService,
        private ThongbaoService $thongbaoService
    ) {
        //
    }

    public function tinhPhiVanChuyen(int $tamTinh): int
    {
        if ($tamTinh >= 500000) {
            return 0;
        }

        return 30000;
    }

    public function datHang(array $dulieu, array $giohang): Donhang
    {
        if (empty($giohang)) {
            throw new \Exception('Giỏ hàng đang trống.');
        }

        $donhang = DB::transaction(function () use ($dulieu, $giohang) {
            $this->kiemTraTonKho($giohang);

            $tamTinh = collect($giohang)->sum('thanh_tien');
            $phiVanChuyen = $this->tinhPhiVanChuyen($tamTinh);
            $tongTien = $tamTinh + $phiVanChuyen;

            $khachhang = Khachhang::updateOrCreate(
                [
                    'so_dien_thoai' => $dulieu['so_dien_thoai'],
                ],
                [
                    'ho_ten' => $dulieu['ho_ten'],
                    'email' => $dulieu['email'] ?? null,
                    'dia_chi' => $dulieu['dia_chi'],
                ]
            );

            $donhang = Donhang::create([
                'khachhang_id' => $khachhang->id,
                'ma_don_hang' => $this->taoMaDonHang(),
                'ho_ten_nguoi_nhan' => $dulieu['ho_ten'],
                'so_dien_thoai_nguoi_nhan' => $dulieu['so_dien_thoai'],
                'email_nguoi_nhan' => $dulieu['email'] ?? null,
                'dia_chi_giao_hang' => $dulieu['dia_chi'],
                'ghi_chu' => $dulieu['ghi_chu'] ?? null,
                'tam_tinh' => $tamTinh,
                'phi_van_chuyen' => $phiVanChuyen,
                'tong_tien' => $tongTien,
                'phuong_thuc_thanh_toan' => $dulieu['phuong_thuc_thanh_toan'],
                'trang_thai_thanh_toan' => match ($dulieu['phuong_thuc_thanh_toan']) {
                    'payos' => 'cho_thanh_toan',
                    'chuyen_khoan' => 'cho_thanh_toan',
                    default => 'chua_thanh_toan',
                },
                'trang_thai_don_hang' => 'cho_xac_nhan',
                'cong_thanh_toan' => $dulieu['phuong_thuc_thanh_toan'] === 'payos'
                    ? 'payos'
                    : null,
            ]);

            foreach ($giohang as $item) {
                $sanpham = Sanpham::lockForUpdate()->findOrFail($item['sanpham_id']);

                if ($sanpham->so_luong_ton < $item['so_luong']) {
                    throw new \Exception('Sản phẩm ' . $sanpham->ten_san_pham . ' không đủ tồn kho.');
                }

                Chitietdonhang::create([
                    'donhang_id' => $donhang->id,
                    'sanpham_id' => $sanpham->id,
                    'ten_san_pham' => $sanpham->ten_san_pham,
                    'ma_san_pham' => $sanpham->ma_san_pham,
                    'anh_san_pham' => $sanpham->anh_dai_dien,
                    'don_gia' => $item['don_gia'],
                    'so_luong' => $item['so_luong'],
                    'thanh_tien' => $item['thanh_tien'],
                ]);

                $sanpham->decrement('so_luong_ton', $item['so_luong']);
            }

            return $donhang;
        });

        $donhang->load('chitietdonhang.sanpham');

        $this->taoThongBaoSauKhiDatHang($donhang);

        cache()->forget('dashboard_thong_ke');

        $this->giohangService->xoaTatCa();

        return $donhang;
    }

    private function kiemTraTonKho(array $giohang): void
    {
        foreach ($giohang as $item) {
            $sanpham = Sanpham::find($item['sanpham_id']);

            if (!$sanpham || !$sanpham->trang_thai) {
                throw new \Exception('Một sản phẩm trong giỏ hàng không còn khả dụng.');
            }

            if ($sanpham->so_luong_ton < $item['so_luong']) {
                throw new \Exception('Sản phẩm ' . $sanpham->ten_san_pham . ' không đủ tồn kho.');
            }
        }
    }

    private function taoThongBaoSauKhiDatHang(Donhang $donhang): void
    {
        $thongbaoDonHangMoi = $this->thongbaoService->taoThongBaoDonHangMoi($donhang);

        try {
            broadcast(new DonhangMoiEvent($donhang, $thongbaoDonHangMoi));
        } catch (\Throwable $e) {
            report($e);
        }

        foreach ($donhang->chitietdonhang as $chitiet) {
            if ($chitiet->sanpham) {
                $this->thongbaoService->taoThongBaoSanPhamGanHet($chitiet->sanpham);
            }
        }
    }

    private function taoMaDonHang(): string
    {
        do {
            $ma = 'DH' . now()->format('ymdHis') . random_int(10, 99);
        } while (Donhang::where('ma_don_hang', $ma)->exists());

        return $ma;
    }
}
