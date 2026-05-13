<?php

namespace App\Repositories;

use App\Models\Donhang;
use Illuminate\Support\Carbon;

class DonhangRepository
{
    public function layDonHangMoiNhat(int $limit = 6)
    {
        return Donhang::query()
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
}
