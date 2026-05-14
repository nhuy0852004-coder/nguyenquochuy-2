<?php

namespace App\Services;

use App\Events\CapnhattrangthaidonhangEvent;
use App\Models\Chitietdonhang;
use App\Models\Donhang;
use App\Models\Khachhang;
use App\Models\Nhatkyhoatdong;
use App\Models\Sanpham;
use App\Repositories\DonhangRepository;
use Illuminate\Support\Facades\DB;

class DonhangService
{
    public function __construct(
        private DonhangRepository $donhangRepository,
        private ThongbaoService $thongbaoService,
        private NhatkyhoatdongService $nhatkyhoatdongService
    ) {
        //
    }

    public function layDanhSachAdmin(?string $tukhoa, ?string $trangthai)
    {
        return $this->donhangRepository->timKiemAdmin($tukhoa, $trangthai);
    }

    public function capNhatTrangThai(Donhang $donhang, string $trangThaiMoi): void
    {
        $trangThaiCu = $donhang->trang_thai_don_hang;
        $duLieuCu = $donhang->toArray();

        if ($donhang->daHoanThanh() && $trangThaiMoi === Donhang::TRANG_THAI_DA_HUY) {
            throw new \Exception('Không thể hủy đơn hàng đã hoàn thành.');
        }

        if ($donhang->daHuy() && $trangThaiMoi !== Donhang::TRANG_THAI_DA_HUY) {
            throw new \Exception('Đơn hàng đã hủy không thể chuyển sang trạng thái khác.');
        }

        DB::transaction(function () use ($donhang, $trangThaiMoi, $trangThaiCu, $duLieuCu) {
            $this->donhangRepository->capNhat($donhang, [
                'trang_thai_don_hang' => $trangThaiMoi,
            ]);

            $donhang->refresh();

            if ($trangThaiMoi === Donhang::TRANG_THAI_DA_HUY && !$donhang->da_hoan_kho) {
                $this->hoanKhoDonHang($donhang);
            }

            if ($trangThaiMoi === Donhang::TRANG_THAI_DA_HUY && $trangThaiCu !== Donhang::TRANG_THAI_DA_HUY) {
                $this->thongbaoService->taoThongBaoDonHangBiHuy($donhang);
            }

            $this->nhatkyhoatdongService->ghiDoiTrangThai(
                $donhang,
                $duLieuCu,
                'Cập nhật trạng thái đơn hàng',
                'Đơn hàng ' . $donhang->ma_don_hang . ' chuyển sang trạng thái: ' . $donhang->trangThaiDonHangText()
            );
        });

        $donhang->refresh();

        try {
            broadcast(new CapnhattrangthaidonhangEvent($donhang));
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function hoanKhoDonHang(Donhang $donhang): void
    {
        $donhang->loadMissing('chitietdonhang.sanpham');

        foreach ($donhang->chitietdonhang as $chitiet) {
            if ($chitiet->sanpham) {
                $chitiet->sanpham->increment('so_luong_ton', $chitiet->so_luong);
            }
        }

        $donhang->update([
            'da_hoan_kho' => true,
            'hoan_kho_luc' => now(),
        ]);

        $this->nhatkyhoatdongService->ghi(
            Nhatkyhoatdong::HANH_DONG_DOI_TRANG_THAI,
            'Hoàn kho đơn hàng',
            'Đã hoàn lại tồn kho cho đơn hàng: ' . $donhang->ma_don_hang,
            'Donhang',
            $donhang->id
        );
    }
}
