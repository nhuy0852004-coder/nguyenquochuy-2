<?php

namespace App\Console\Commands;

use App\Models\Danhmuc;
use App\Models\Donhang;
use App\Models\Khachhang;
use App\Models\Nguoidung;
use App\Models\Sanpham;
use App\Models\Thongbao;
use Illuminate\Console\Command;

class KiemtrahethongCommand extends Command
{
    protected $signature = 'hethong:kiemtra';

    protected $description = 'Kiểm tra nhanh số liệu chính của hệ thống bán hàng';

    public function handle(): int
    {
        $this->info('KIỂM TRA HỆ THỐNG BÁN HÀNG');
        $this->line('--------------------------------');

        $this->line('Người dùng: ' . Nguoidung::count());
        $this->line('Danh mục: ' . Danhmuc::count());
        $this->line('Sản phẩm: ' . Sanpham::count());
        $this->line('Khách hàng: ' . Khachhang::count());
        $this->line('Đơn hàng: ' . Donhang::count());
        $this->line('Thông báo: ' . Thongbao::count());

        $doanhthu = Donhang::query()
            ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY)
            ->sum('tong_tien');

        $this->line('Doanh thu hợp lệ: ' . number_format($doanhthu, 0, ',', '.') . ' ₫');

        $sanphamganhet = Sanpham::query()
            ->whereColumn('so_luong_ton', '<=', 'muc_canh_bao_ton')
            ->count();

        $this->line('Sản phẩm gần hết hàng: ' . $sanphamganhet);

        $this->info('Hoàn tất kiểm tra.');

        return self::SUCCESS;
    }
}
