<?php

namespace App\Repositories;

use App\Models\Thongbao;

class ThongbaoRepository
{
    public function demChuaDoc(): int
    {
        return Thongbao::query()
            ->where('da_doc', false)
            ->count();
    }

    public function layMoiNhat(int $limit = 5)
    {
        return Thongbao::query()
            ->select('id', 'tieu_de', 'noi_dung', 'loai', 'duong_dan', 'da_doc', 'created_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function locPhanTrang(?string $trangthai, ?string $loai, int $limit = 15)
    {
        return Thongbao::query()
            ->when($trangthai === 'chua_doc', function ($query) {
                $query->where('da_doc', false);
            })
            ->when($trangthai === 'da_doc', function ($query) {
                $query->where('da_doc', true);
            })
            ->when($loai, function ($query) use ($loai) {
                $query->where('loai', $loai);
            })
            ->orderByDesc('id')
            ->paginate($limit)
            ->withQueryString();
    }
}
