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
        'ma_giao_dich_thanh_toan',
        'ma_phan_hoi_thanh_toan',
        'cong_thanh_toan',
        'thanh_toan_luc',
        'da_hoan_kho',
        'hoan_kho_luc',
    ];

    protected $casts = [
        'tam_tinh' => 'integer',
        'phi_van_chuyen' => 'integer',
        'tong_tien' => 'integer',
        'cong_thanh_toan' => 'integer',
        'da_hoan_kho' => 'boolean',
        'hoan_kho_luc' => 'datetime',
        'thanh_toan_luc' => 'datetime',
    ];

    public const TRANG_THAI_CHO_XAC_NHAN = 'cho_xac_nhan';
    public const TRANG_THAI_DA_XAC_NHAN = 'da_xac_nhan';
    public const TRANG_THAI_DANG_GIAO_HANG = 'dang_giao_hang';
    public const TRANG_THAI_HOAN_THANH = 'hoan_thanh';
    public const TRANG_THAI_DA_HUY = 'da_huy';

    public function khachhang()
    {
        return $this->belongsTo(Khachhang::class, 'khachhang_id');
    }

    public function chitietdonhang()
    {
        return $this->hasMany(Chitietdonhang::class, 'donhang_id');
    }

    public static function danhSachTrangThai(): array
    {
        return [
            self::TRANG_THAI_CHO_XAC_NHAN => 'Chờ xác nhận',
            self::TRANG_THAI_DA_XAC_NHAN => 'Đã xác nhận',
            self::TRANG_THAI_DANG_GIAO_HANG => 'Đang giao hàng',
            self::TRANG_THAI_HOAN_THANH => 'Hoàn thành',
            self::TRANG_THAI_DA_HUY => 'Đã hủy',
        ];
    }

    public function trangThaiDonHangText(): string
    {
        return self::danhSachTrangThai()[$this->trang_thai_don_hang] ?? 'Không xác định';
    }

    public function trangThaiDonHangClass(): string
    {
        return match ($this->trang_thai_don_hang) {
            self::TRANG_THAI_CHO_XAC_NHAN => 'badge-cho',
            self::TRANG_THAI_DA_XAC_NHAN => 'badge-xacnhan',
            self::TRANG_THAI_DANG_GIAO_HANG => 'badge-giao',
            self::TRANG_THAI_HOAN_THANH => 'badge-hoanthanh',
            self::TRANG_THAI_DA_HUY => 'badge-huy',
            default => 'badge-tat',
        };
    }

    public function phuongThucThanhToanText(): string
    {
        return match ($this->phuong_thuc_thanh_toan) {
            'cod' => 'Thanh toán khi nhận hàng',
            'chuyen_khoan' => 'Chuyển khoản ngân hàng',
            'vnpay' => 'Thanh toán VNPay',
            default => 'Không xác định',
        };
    }

    public function trangThaiThanhToanText(): string
    {
        return match ($this->trang_thai_thanh_toan) {
            'chua_thanh_toan' => 'Chưa thanh toán',
            'cho_thanh_toan' => 'Chờ thanh toán',
            'da_thanh_toan' => 'Đã thanh toán',
            'thanh_toan_that_bai' => 'Thanh toán thất bại',
            default => 'Không xác định',
        };
    }

    public function daHuy(): bool
    {
        return $this->trang_thai_don_hang === self::TRANG_THAI_DA_HUY;
    }

    public function daHoanThanh(): bool
    {
        return $this->trang_thai_don_hang === self::TRANG_THAI_HOAN_THANH;
    }

    public function canHoanKho(): bool
    {
        return $this->daHuy() && !$this->da_hoan_kho;
    }
}
