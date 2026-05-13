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
            ->orderBy('thu_tu')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function timKiemPhanTrang(?string $tukhoa = null, int $limit = 10)
    {
        return Danhmuc::query()
            ->when($tukhoa, function ($query) use ($tukhoa) {
                $query->where('ten_danh_muc', 'like', '%' . $tukhoa . '%')
                    ->orWhere('duong_dan', 'like', '%' . $tukhoa . '%')
                    ->orWhere('mo_ta', 'like', '%' . $tukhoa . '%');
            })
            ->orderBy('thu_tu')
            ->orderByDesc('id')
            ->paginate($limit)
            ->withQueryString();
    }
}
