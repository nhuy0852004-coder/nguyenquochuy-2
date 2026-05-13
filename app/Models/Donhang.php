<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donhang extends Model
{
    protected $table = 'donhang';

    protected $fillable = [
        'khachhang_id',
        'ma_don_hang',
        'ho_ten_nguoi_nhan',
        'so_dien_thoai_nguoi_nhan',
        'email_nguoi_nhan',
        'dia_chi_giao_hang',
        'ghi_chu',
        'tam_tinh',
        'phi_van_chuyen',
        'tong_tien',
        'phuong_thuc_thanh_toan',
        'trang_thai_thanh_toan',
        'trang_thai_don_hang',
    ];

    protected $casts = [
        'tam_tinh' => 'integer',
        'phi_van_chuyen' => 'integer',
        'tong_tien' => 'integer',
    ];

    public function khachhang()
    {
        return $this->belongsTo(Khachhang::class, 'khachhang_id');
    }

    public function chitietdonhang()
    {
        return $this->hasMany(Chitietdonhang::class, 'donhang_id');
    }

    public function trangThaiDonHangText(): string
    {
        return match ($this->trang_thai_don_hang) {
            'cho_xac_nhan' => 'Chờ xác nhận',
            'da_xac_nhan' => 'Đã xác nhận',
            'dang_giao_hang' => 'Đang giao hàng',
            'hoan_thanh' => 'Hoàn thành',
            'da_huy' => 'Đã hủy',
            default => 'Không xác định',
        };
    }

    public function phuongThucThanhToanText(): string
    {
        return match ($this->phuong_thuc_thanh_toan) {
            'cod' => 'Thanh toán khi nhận hàng',
            'chuyen_khoan' => 'Chuyển khoản ngân hàng',
            default => 'Không xác định',
        };
    }
}
