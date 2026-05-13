<?php

namespace App\Services;

use App\Events\CapnhattrangthaidonhangEvent;
use App\Models\Donhang;
use App\Repositories\DonhangRepository;

class DonhangService
{
    public function __construct(
        private DonhangRepository $donhangRepository,
        private ThongbaoService $thongbaoService
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

        if ($trangThaiCu === Donhang::TRANG_THAI_HOAN_THANH && $trangThaiMoi === Donhang::TRANG_THAI_DA_HUY) {
            throw new \Exception('Không thể hủy đơn hàng đã hoàn thành.');
        }

        $this->donhangRepository->capNhat($donhang, [
            'trang_thai_don_hang' => $trangThaiMoi,
        ]);

        $donhang->refresh();

        if ($trangThaiMoi === Donhang::TRANG_THAI_DA_HUY && $trangThaiCu !== Donhang::TRANG_THAI_DA_HUY) {
            $this->thongbaoService->taoThongBaoDonHangBiHuy($donhang);
        }

        try {
            broadcast(new CapnhattrangthaidonhangEvent($donhang));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}