<?php

namespace App\Repositories;

use App\Models\Donhang;
use Illuminate\Support\Carbon;

class DonhangRepository
{
    public function layDonHangMoiNhat(int $limit = 6)
    {
        return Donhang::query()
            ->select(
                'id',
                'ma_don_hang',
                'ho_ten_nguoi_nhan',
                'so_dien_thoai_nguoi_nhan',
                'tong_tien',
                'trang_thai_don_hang',
                'created_at'
            )
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function demDonHangHomNay(): int
    {
        return Donhang::query()
            ->whereDate('created_at', now()->toDateString())
            ->count();
    }

    public function tinhDoanhThuHomNay(): int
    {
        return (int) Donhang::query()
            ->whereDate('created_at', now()->toDateString())
            ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY)
            ->sum('tong_tien');
    }

    public function tinhDoanhThuTheoKhoang(Carbon $tuNgay, Carbon $denNgay): int
    {
        return (int) Donhang::query()
            ->whereBetween('created_at', [$tuNgay, $denNgay])
            ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY)
            ->sum('tong_tien');
    }

    public function demDonHangTheoKhoang(Carbon $tuNgay, Carbon $denNgay): int
    {
        return Donhang::query()
            ->whereBetween('created_at', [$tuNgay, $denNgay])
            ->count();
    }

    public function demDonHopLeTheoKhoang(Carbon $tuNgay, Carbon $denNgay): int
    {
        return Donhang::query()
            ->whereBetween('created_at', [$tuNgay, $denNgay])
            ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY)
            ->count();
    }

    public function demDonHuyTheoKhoang(Carbon $tuNgay, Carbon $denNgay): int
    {
        return Donhang::query()
            ->whereBetween('created_at', [$tuNgay, $denNgay])
            ->where('trang_thai_don_hang', Donhang::TRANG_THAI_DA_HUY)
            ->count();
    }

    public function layDonHangTrongKhoang(Carbon $tuNgay, Carbon $denNgay, int $limit = 20)
    {
        return Donhang::query()
            ->whereBetween('created_at', [$tuNgay, $denNgay])
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function timKiemAdmin(?string $tukhoa, ?string $trangthai, int $limit = 10)
    {
        return Donhang::query()
            ->select(
                'id',
                'khachhang_id',
                'ma_don_hang',
                'ho_ten_nguoi_nhan',
                'so_dien_thoai_nguoi_nhan',
                'email_nguoi_nhan',
                'tong_tien',
                'phuong_thuc_thanh_toan',
                'trang_thai_thanh_toan',
                'trang_thai_don_hang',
                'created_at'
            )
            ->with('khachhang:id,ho_ten,so_dien_thoai,email')
            ->when($tukhoa, function ($query) use ($tukhoa) {
                $query->where(function ($q) use ($tukhoa) {
                    $q->where('ma_don_hang', 'like', '%' . $tukhoa . '%')
                        ->orWhere('ho_ten_nguoi_nhan', 'like', '%' . $tukhoa . '%')
                        ->orWhere('so_dien_thoai_nguoi_nhan', 'like', '%' . $tukhoa . '%')
                        ->orWhere('email_nguoi_nhan', 'like', '%' . $tukhoa . '%');
                });
            })
            ->when($trangthai, function ($query) use ($trangthai) {
                $query->where('trang_thai_don_hang', $trangthai);
            })
            ->orderByDesc('id')
            ->paginate($limit)
            ->withQueryString();
    }

    public function capNhat(Donhang $donhang, array $dulieu): bool
    {
        return $donhang->update($dulieu);
    }
}
