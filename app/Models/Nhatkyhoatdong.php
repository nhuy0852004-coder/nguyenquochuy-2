<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nhatkyhoatdong extends Model
{
    protected $table = 'nhatkyhoatdong';

    protected $fillable = [
        'nguoidung_id',
        'hanh_dong',
        'doi_tuong',
        'doi_tuong_id',
        'tieu_de',
        'noi_dung',
        'dia_chi_ip',
        'trinh_duyet',
        'du_lieu_cu',
        'du_lieu_moi',
    ];

    protected $casts = [
        'du_lieu_cu' => 'array',
        'du_lieu_moi' => 'array',
    ];

    public const HANH_DONG_DANG_NHAP = 'dang_nhap';
    public const HANH_DONG_DANG_XUAT = 'dang_xuat';
    public const HANH_DONG_THEM = 'them';
    public const HANH_DONG_SUA = 'sua';
    public const HANH_DONG_XOA = 'xoa';
    public const HANH_DONG_DOI_TRANG_THAI = 'doi_trang_thai';
    public const HANH_DONG_DOI_MAT_KHAU = 'doi_mat_khau';

    public function nguoidung()
    {
        return $this->belongsTo(Nguoidung::class, 'nguoidung_id');
    }

    public function hanhDongText(): string
    {
        return match ($this->hanh_dong) {
            self::HANH_DONG_DANG_NHAP => 'Đăng nhập',
            self::HANH_DONG_DANG_XUAT => 'Đăng xuất',
            self::HANH_DONG_THEM => 'Thêm mới',
            self::HANH_DONG_SUA => 'Cập nhật',
            self::HANH_DONG_XOA => 'Xóa',
            self::HANH_DONG_DOI_TRANG_THAI => 'Đổi trạng thái',
            self::HANH_DONG_DOI_MAT_KHAU => 'Đổi mật khẩu',
            default => 'Khác',
        };
    }

    public function hanhDongClass(): string
    {
        return match ($this->hanh_dong) {
            self::HANH_DONG_DANG_NHAP => 'log-login',
            self::HANH_DONG_DANG_XUAT => 'log-logout',
            self::HANH_DONG_THEM => 'log-create',
            self::HANH_DONG_SUA => 'log-update',
            self::HANH_DONG_XOA => 'log-delete',
            self::HANH_DONG_DOI_TRANG_THAI => 'log-status',
            self::HANH_DONG_DOI_MAT_KHAU => 'log-password',
            default => 'log-default',
        };
    }
}
