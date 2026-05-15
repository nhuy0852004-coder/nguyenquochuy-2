<?php

namespace App\Repositories;

use App\Models\Danhmuc;

class DanhmucRepository
{
    public function layDanhMucDangBat()
    {
        return Danhmuc::query()
            ->where('trang_thai', true)
            ->orderBy('thu_tu')
            ->orderByDesc('id')
            ->get();
    }

    public function layDanhMucNoiBat(int $limit = 8)
    {
        return Danhmuc::query()
            ->where('trang_thai', true)
            ->whereNull('parent_id')
            ->orderBy('thu_tu')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function layTatCaDanhMuc()
    {
        return Danhmuc::query()
            ->with('cha')
            ->withCount(['con', 'sanpham'])
            ->orderBy('parent_id')
            ->orderBy('thu_tu')
            ->orderBy('ten_danh_muc')
            ->get();
    }

    public function layDanhMucDangCay()
    {
        return Danhmuc::query()
            ->with([
                'con' => function ($query) {
                    $query->withCount(['con', 'sanpham']);
                },
                'con.con' => function ($query) {
                    $query->withCount(['con', 'sanpham']);
                },
                'con.con.con' => function ($query) {
                    $query->withCount(['con', 'sanpham']);
                },
            ])
            ->withCount(['con', 'sanpham'])
            ->whereNull('parent_id')
            ->orderBy('thu_tu')
            ->orderBy('ten_danh_muc')
            ->get();
    }

    public function timKiemPhanTrang(
        ?string $tukhoa = null,
        ?string $parentId = null,
        ?string $kieuDanhMuc = null,
        mixed $trangthai = null,
        ?string $soLuongSanPham = null,
        ?string $sapxep = 'thu_tu',
        string $cotSapXep = 'thu_tu',
        string $huongSapXep = 'asc',
        int $limit = 10
    ) {
        $query = Danhmuc::query()
            ->with('cha')
            ->withCount(['con', 'sanpham'])
            ->when($tukhoa, function ($query) use ($tukhoa) {
                $query->where(function ($q) use ($tukhoa) {
                    $q->where('ten_danh_muc', 'like', '%' . $tukhoa . '%')
                        ->orWhere('duong_dan', 'like', '%' . $tukhoa . '%')
                        ->orWhere('mo_ta', 'like', '%' . $tukhoa . '%');
                });
            })
            ->when($parentId !== null && $parentId !== '', function ($query) use ($parentId) {
                if ($parentId === 'goc') {
                    $query->whereNull('parent_id');
                } else {
                    $query->where('parent_id', $parentId);
                }
            })
            ->when($kieuDanhMuc === 'goc', function ($query) {
                $query->whereNull('parent_id');
            })
            ->when($kieuDanhMuc === 'con', function ($query) {
                $query->whereNotNull('parent_id');
            })
            ->when($trangthai !== null && $trangthai !== '', function ($query) use ($trangthai) {
                $query->where('trang_thai', (bool) $trangthai);
            });

        if ($soLuongSanPham) {
            if ($soLuongSanPham === 'khong_co') {
                $query->has('sanpham', '=', 0);
            }

            if ($soLuongSanPham === 'co_san_pham') {
                $query->has('sanpham', '>', 0);
            }

            if ($soLuongSanPham === 'duoi_5') {
                $query->has('sanpham', '>', 0)
                    ->has('sanpham', '<', 5);
            }

            if ($soLuongSanPham === 'tu_5_20') {
                $query->has('sanpham', '>=', 5)
                    ->has('sanpham', '<=', 20);
            }

            if ($soLuongSanPham === 'tren_20') {
                $query->has('sanpham', '>', 20);
            }
        }

        $cotChoPhep = [
            'id',
            'ten_danh_muc',
            'thu_tu',
            'trang_thai',
            'created_at',
            'sanpham_count',
            'con_count',
        ];

        $huongSapXep = strtolower($huongSapXep) === 'desc' ? 'desc' : 'asc';

        if (in_array($cotSapXep, $cotChoPhep, true)) {
            $query->orderBy($cotSapXep, $huongSapXep);
        } else {
            if ($sapxep === 'moi_tao') {
                $query->orderByDesc('id');
            } elseif ($sapxep === 'cu_nhat') {
                $query->orderBy('id');
            } elseif ($sapxep === 'nhieu_san_pham') {
                $query->orderByDesc('sanpham_count');
            } elseif ($sapxep === 'nhieu_danh_muc_con') {
                $query->orderByDesc('con_count');
            } elseif ($sapxep === 'ten_az') {
                $query->orderBy('ten_danh_muc');
            } else {
                $query->orderByRaw('parent_id IS NOT NULL')
                    ->orderBy('parent_id')
                    ->orderBy('thu_tu')
                    ->orderByDesc('id');
            }
        }

        return $query
            ->paginate($limit)
            ->withQueryString();
    }

    public function tao(array $dulieu): Danhmuc
    {
        return Danhmuc::create($dulieu);
    }

    public function capNhat(Danhmuc $danhmuc, array $dulieu): bool
    {
        return $danhmuc->update($dulieu);
    }

    public function xoa(Danhmuc $danhmuc): bool
    {
        return $danhmuc->delete();
    }
}
