<?php

namespace App\Services;

use App\Models\Danhmuc;
use App\Repositories\DanhmucRepository;
use Illuminate\Support\Str;

class DanhmucService
{
    public function __construct(
        private DanhmucRepository $danhmucRepository
    ) {
        //
    }

    public function layDanhSach(?string $tukhoa = null, int $limit = 10)
    {
        return $this->danhmucRepository->timKiemPhanTrang($tukhoa, $limit);
    }

    public function taoDanhMuc(array $dulieu, bool $trangThai): Danhmuc
    {
        $dulieu['duong_dan'] = $this->taoDuongDan(
            $dulieu['duong_dan'] ?? $dulieu['ten_danh_muc']
        );

        $dulieu['thu_tu'] = $dulieu['thu_tu'] ?? 0;
        $dulieu['trang_thai'] = $trangThai;

        return $this->danhmucRepository->tao($dulieu);
    }

    public function capNhatDanhMuc(Danhmuc $danhmuc, array $dulieu, bool $trangThai): bool
    {
        $dulieu['duong_dan'] = $this->taoDuongDan(
            $dulieu['duong_dan'] ?? $dulieu['ten_danh_muc']
        );

        $dulieu['thu_tu'] = $dulieu['thu_tu'] ?? 0;
        $dulieu['trang_thai'] = $trangThai;

        return $this->danhmucRepository->capNhat($danhmuc, $dulieu);
    }

    public function xoaDanhMuc(Danhmuc $danhmuc): bool
    {
        return $this->danhmucRepository->xoa($danhmuc);
    }

    public function doiTrangThai(Danhmuc $danhmuc): bool
    {
        return $this->danhmucRepository->capNhat($danhmuc, [
            'trang_thai' => !$danhmuc->trang_thai,
        ]);
    }

    private function taoDuongDan(string $chuoi): string
    {
        return Str::slug($chuoi);
    }
}