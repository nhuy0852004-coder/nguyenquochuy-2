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
            ->whereColumn('so_luong_ton', '<=', 'muc_canh_bao_ton')
            ->orderBy('so_luong_ton')
            ->limit($limit)
            ->get();
    }

    public function timKiemSanPhamWebsite(?string $tukhoa, ?string $danhmucSlug, string $sapxep = 'moi_nhat', int $limit = 12)
    {
        $query = Sanpham::query()
            ->with('danhmuc')
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
            });

        if ($sapxep === 'gia_thap') {
            $query->orderByRaw('COALESCE(gia_khuyen_mai, gia_ban) ASC');
        } elseif ($sapxep === 'gia_cao') {
            $query->orderByRaw('COALESCE(gia_khuyen_mai, gia_ban) DESC');
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
}
