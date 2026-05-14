<?php

namespace App\Services;

use App\Models\Danhmuc;
use App\Repositories\DanhmucRepository;
use Illuminate\Support\Str;

class DanhmucService
{
    public function __construct(
        private DanhmucRepository $danhmucRepository,
        private NhatkyhoatdongService $nhatkyhoatdongService
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

        $danhmuc = $this->danhmucRepository->tao($dulieu);

        $this->nhatkyhoatdongService->ghiThem(
            $danhmuc,
            'Thêm danh mục',
            'Đã thêm danh mục: ' . $danhmuc->ten_danh_muc
        );

        return $danhmuc;
    }

    public function capNhatDanhMuc(Danhmuc $danhmuc, array $dulieu, bool $trangThai): bool
    {
        $duLieuCu = $danhmuc->toArray();

        $dulieu['duong_dan'] = $this->taoDuongDan(
            $dulieu['duong_dan'] ?? $dulieu['ten_danh_muc']
        );

        $dulieu['thu_tu'] = $dulieu['thu_tu'] ?? 0;
        $dulieu['trang_thai'] = $trangThai;

        $ketqua = $this->danhmucRepository->capNhat($danhmuc, $dulieu);

        $this->nhatkyhoatdongService->ghiSua(
            $danhmuc,
            $duLieuCu,
            'Cập nhật danh mục',
            'Đã cập nhật danh mục: ' . $danhmuc->fresh()->ten_danh_muc
        );

        return $ketqua;
    }

    public function xoaDanhMuc(Danhmuc $danhmuc): bool
    {
        if ($danhmuc->sanpham()->exists()) {
            throw new \Exception('Không thể xóa danh mục này vì đang có sản phẩm thuộc danh mục.');
        }

        $this->nhatkyhoatdongService->ghiXoa(
            $danhmuc,
            'Xóa danh mục',
            'Đã xóa danh mục: ' . $danhmuc->ten_danh_muc
        );

        return $this->danhmucRepository->xoa($danhmuc);
    }

    public function doiTrangThai(Danhmuc $danhmuc): bool
    {
        $duLieuCu = $danhmuc->toArray();

        $ketqua = $this->danhmucRepository->capNhat($danhmuc, [
            'trang_thai' => !$danhmuc->trang_thai,
        ]);

        $this->nhatkyhoatdongService->ghiDoiTrangThai(
            $danhmuc,
            $duLieuCu,
            'Đổi trạng thái danh mục',
            'Đã đổi trạng thái danh mục: ' . $danhmuc->fresh()->ten_danh_muc
        );

        return $ketqua;
    }

    private function taoDuongDan(string $chuoi): string
    {
        return Str::slug($chuoi);
    }
}