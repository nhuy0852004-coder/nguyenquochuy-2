<?php

namespace App\Repositories;

use App\Models\Nguoidung;

class NguoidungRepository
{
    public function timKiemPhanTrang(?string $tukhoa = null, int $limit = 10)
    {
        return Nguoidung::query()
            ->when($tukhoa, function ($query) use ($tukhoa) {
                $query->where(function ($q) use ($tukhoa) {
                    $q->where('ho_ten', 'like', '%' . $tukhoa . '%')
                        ->orWhere('email', 'like', '%' . $tukhoa . '%')
                        ->orWhere('vai_tro', 'like', '%' . $tukhoa . '%');
                });
            })
            ->orderByDesc('id')
            ->paginate($limit)
            ->withQueryString();
    }

    public function tao(array $dulieu): Nguoidung
    {
        return Nguoidung::create($dulieu);
    }

    public function capNhat(Nguoidung $nguoidung, array $dulieu): bool
    {
        return $nguoidung->update($dulieu);
    }

    public function xoa(Nguoidung $nguoidung): bool
    {
        return $nguoidung->delete();
    }
}
