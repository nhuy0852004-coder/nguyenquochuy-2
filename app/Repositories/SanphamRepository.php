<?php

namespace App\Repositories;

use App\Models\Sanpham;

class SanphamRepository
{
    public function laySanPhamMoi(int $limit = 8)
    {
        return Sanpham::query()
            ->with('danhmuc')
            ->where('trang_thai', true)
            ->where('so_luong_ton', '>', 0)
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function laySanPhamKhuyenMai(int $limit = 8)
    {
        return Sanpham::query()
            ->with('danhmuc')
            ->where('trang_thai', true)
            ->whereNotNull('gia_khuyen_mai')
            ->where('gia_khuyen_mai', '>', 0)
            ->where('so_luong_ton', '>', 0)
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function laySanPhamNoiBat(int $limit = 8)
    {
        return Sanpham::query()
            ->with('danhmuc')
            ->where('trang_thai', true)
            ->where('noi_bat', true)
            ->where('so_luong_ton', '>', 0)
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function laySanPhamGanHet(int $limit = 6)
    {
        return Sanpham::query()
            ->select('id', 'ten_san_pham', 'so_luong_ton', 'muc_canh_bao_ton')
            ->whereColumn('so_luong_ton', '<=', 'muc_canh_bao_ton')
            ->orderBy('so_luong_ton')
            ->limit($limit)
            ->get();
    }

    public function timKiemSanPhamWebsite(
        ?string $tukhoa,
        ?string $danhmucSlug,
        string $sapxep = 'moi_nhat',
        ?string $khoangGia = null,
        bool $chiConHang = false,
        bool $chiKhuyenMai = false,
        bool $chiNoiBat = false,
        int $limit = 12
    ) {
        $query = Sanpham::query()
            ->select(
                'id',
                'danhmuc_id',
                'ten_san_pham',
                'duong_dan',
                'ma_san_pham',
                'gia_ban',
                'gia_khuyen_mai',
                'so_luong_ton',
                'muc_canh_bao_ton',
                'anh_dai_dien',
                'mo_ta_ngan',
                'trang_thai',
                'noi_bat',
                'created_at'
            )
            ->with('danhmuc:id,ten_danh_muc,duong_dan')
            ->where('trang_thai', true)
            ->when($tukhoa, function ($query) use ($tukhoa) {
                $query->where(function ($q) use ($tukhoa) {
                    $q->where('ten_san_pham', 'like', '%' . $tukhoa . '%')
                        ->orWhere('mo_ta_ngan', 'like', '%' . $tukhoa . '%')
                        ->orWhere('ma_san_pham', 'like', '%' . $tukhoa . '%');
                });
            })
            ->when($danhmucSlug, function ($query) use ($danhmucSlug) {
                $query->whereHas('danhmuc', function ($q) use ($danhmucSlug) {
                    $q->where('duong_dan', $danhmucSlug);
                });
            })
            ->when($chiConHang, function ($query) {
                $query->where('so_luong_ton', '>', 0);
            })
            ->when($chiKhuyenMai, function ($query) {
                $query->whereNotNull('gia_khuyen_mai')
                    ->where('gia_khuyen_mai', '>', 0);
            })
            ->when($chiNoiBat, function ($query) {
                $query->where('noi_bat', true);
            });

        if ($khoangGia) {
            $query->where(function ($q) use ($khoangGia) {
                if ($khoangGia === 'duoi_200') {
                    $q->whereRaw('IFNULL(gia_khuyen_mai, gia_ban) < 200000');
                }

                if ($khoangGia === '200_500') {
                    $q->whereRaw('IFNULL(gia_khuyen_mai, gia_ban) BETWEEN 200000 AND 500000');
                }

                if ($khoangGia === '500_1000') {
                    $q->whereRaw('IFNULL(gia_khuyen_mai, gia_ban) BETWEEN 500000 AND 1000000');
                }

                if ($khoangGia === 'tren_1000') {
                    $q->whereRaw('IFNULL(gia_khuyen_mai, gia_ban) > 1000000');
                }
            });
        }

        if ($sapxep === 'gia_thap') {
            $query->orderByRaw('IFNULL(gia_khuyen_mai, gia_ban) ASC');
        } elseif ($sapxep === 'gia_cao') {
            $query->orderByRaw('IFNULL(gia_khuyen_mai, gia_ban) DESC');
        } else {
            $query->orderByDesc('id');
        }

        return $query->paginate($limit)->withQueryString();
    }

    public function layChiTietTheoDuongDan(string $duongdan): Sanpham
    {
        return Sanpham::query()
            ->with('danhmuc')
            ->where('duong_dan', $duongdan)
            ->where('trang_thai', true)
            ->firstOrFail();
    }

    public function laySanPhamLienQuan(Sanpham $sanpham, int $limit = 4)
    {
        return Sanpham::query()
            ->with('danhmuc')
            ->where('trang_thai', true)
            ->where('id', '!=', $sanpham->id)
            ->where('danhmuc_id', $sanpham->danhmuc_id)
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function timKiemSanPhamAdmin(
        ?string $tukhoa,
        ?string $danhmucId,
        mixed $trangthai,
        ?string $tonkho = null,
        ?string $noibat = null,
        ?string $khuyenmai = null,
        int $limit = 10
    ) {
        return Sanpham::query()
            ->select(
                'id',
                'danhmuc_id',
                'ten_san_pham',
                'duong_dan',
                'ma_san_pham',
                'gia_ban',
                'gia_khuyen_mai',
                'so_luong_ton',
                'muc_canh_bao_ton',
                'anh_dai_dien',
                'trang_thai',
                'noi_bat',
                'created_at',
                'updated_at'
            )
            ->with('danhmuc:id,ten_danh_muc')
            ->when($tukhoa, function ($query) use ($tukhoa) {
                $query->where(function ($q) use ($tukhoa) {
                    $q->where('ten_san_pham', 'like', '%' . $tukhoa . '%')
                        ->orWhere('ma_san_pham', 'like', '%' . $tukhoa . '%')
                        ->orWhere('duong_dan', 'like', '%' . $tukhoa . '%');
                });
            })
            ->when($danhmucId, function ($query) use ($danhmucId) {
                $query->where('danhmuc_id', $danhmucId);
            })
            ->when($trangthai !== null && $trangthai !== '', function ($query) use ($trangthai) {
                $query->where('trang_thai', (bool) $trangthai);
            })
            ->when($tonkho === 'con_hang', function ($query) {
                $query->where('so_luong_ton', '>', 0)
                    ->whereColumn('so_luong_ton', '>', 'muc_canh_bao_ton');
            })
            ->when($tonkho === 'gan_het', function ($query) {
                $query->where('so_luong_ton', '>', 0)
                    ->whereColumn('so_luong_ton', '<=', 'muc_canh_bao_ton');
            })
            ->when($tonkho === 'het_hang', function ($query) {
                $query->where('so_luong_ton', '<=', 0);
            })
            ->when($noibat !== null && $noibat !== '', function ($query) use ($noibat) {
                $query->where('noi_bat', (bool) $noibat);
            })
            ->when($khuyenmai === '1', function ($query) {
                $query->whereNotNull('gia_khuyen_mai')
                    ->where('gia_khuyen_mai', '>', 0);
            })
            ->orderByDesc('id')
            ->paginate($limit)
            ->withQueryString();
    }

    public function tao(array $dulieu): Sanpham
    {
        return Sanpham::create($dulieu);
    }

    public function capNhat(Sanpham $sanpham, array $dulieu): bool
    {
        return $sanpham->update($dulieu);
    }

    public function xoa(Sanpham $sanpham): bool
    {
        return $sanpham->delete();
    }
}
