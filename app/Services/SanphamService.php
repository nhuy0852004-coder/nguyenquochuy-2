<?php

namespace App\Services;

use App\Models\Sanpham;
use App\Repositories\SanphamRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SanphamService
{
    public function __construct(
        private SanphamRepository $sanphamRepository
    ) {
        //
    }

    public function layDanhSachAdmin(?string $tukhoa, ?string $danhmucId, mixed $trangthai)
    {
        return $this->sanphamRepository->timKiemSanPhamAdmin(
            $tukhoa,
            $danhmucId,
            $trangthai
        );
    }

    public function taoSanPham(array $dulieu, bool $trangThai, bool $noiBat, ?UploadedFile $anhDaiDien = null): Sanpham
    {
        $dulieu = $this->chuanHoaDuLieuSanPham($dulieu, $trangThai, $noiBat);

        $dulieu['ma_san_pham'] = $dulieu['ma_san_pham'] ?: $this->taoMaSanPham();

        if ($anhDaiDien) {
            $dulieu['anh_dai_dien'] = $this->uploadAnhDaiDien($anhDaiDien);
        }

        return $this->sanphamRepository->tao($dulieu);
    }

    public function capNhatSanPham(Sanpham $sanpham, array $dulieu, bool $trangThai, bool $noiBat, ?UploadedFile $anhDaiDien = null): bool
    {
        $dulieu = $this->chuanHoaDuLieuSanPham($dulieu, $trangThai, $noiBat, $sanpham->id);

        $dulieu['ma_san_pham'] = $dulieu['ma_san_pham'] ?: $sanpham->ma_san_pham;

        if ($anhDaiDien) {
            if ($sanpham->anh_dai_dien) {
                Storage::disk('public')->delete($sanpham->anh_dai_dien);
            }

            $dulieu['anh_dai_dien'] = $this->uploadAnhDaiDien($anhDaiDien);
        }

        return $this->sanphamRepository->capNhat($sanpham, $dulieu);
    }

    public function xoaSanPham(Sanpham $sanpham): bool
    {
        return $this->sanphamRepository->xoa($sanpham);
    }

    public function doiTrangThai(Sanpham $sanpham): bool
    {
        return $this->sanphamRepository->capNhat($sanpham, [
            'trang_thai' => !$sanpham->trang_thai,
        ]);
    }

    private function chuanHoaDuLieuSanPham(array $dulieu, bool $trangThai, bool $noiBat, ?int $boQuaId = null): array
    {
        $dulieu['duong_dan'] = $this->taoDuongDan(
            $dulieu['duong_dan'] ?? $dulieu['ten_san_pham'],
            $boQuaId
        );

        $dulieu['muc_canh_bao_ton'] = $dulieu['muc_canh_bao_ton'] ?? 5;
        $dulieu['trang_thai'] = $trangThai;
        $dulieu['noi_bat'] = $noiBat;

        return $dulieu;
    }

    private function uploadAnhDaiDien(UploadedFile $file): string
    {
        return $file->store('sanpham', 'public');
    }

    private function taoDuongDan(string $chuoi, ?int $boQuaId = null): string
    {
        $duongdan = Str::slug($chuoi);
        $duongdanGoc = $duongdan;
        $dem = 1;

        while (
            Sanpham::where('duong_dan', $duongdan)
                ->when($boQuaId, function ($query) use ($boQuaId) {
                    $query->where('id', '!=', $boQuaId);
                })
                ->exists()
        ) {
            $duongdan = $duongdanGoc . '-' . $dem;
            $dem++;
        }

        return $duongdan;
    }

    private function taoMaSanPham(): string
    {
        $so = Sanpham::withTrashed()->count() + 1;

        return 'SP' . str_pad($so, 5, '0', STR_PAD_LEFT);
    }
}