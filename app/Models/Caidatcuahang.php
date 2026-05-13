<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caidatcuahang extends Model
{
    protected $table = 'caidatcuahang';

    protected $fillable = [
        'ten_cua_hang',
        'logo',
        'so_dien_thoai',
        'email',
        'dia_chi',
        'chinh_sach_van_chuyen',
        'chinh_sach_doi_tra',
        'facebook',
        'zalo',
    ];

    public static function hienTai(): self
    {
        return self::query()->firstOrCreate(
            ['id' => 1],
            [
                'ten_cua_hang' => 'Bán Hàng Việt',
                'so_dien_thoai' => '0901 234 567',
                'email' => 'hotro@banhangviet.vn',
                'dia_chi' => 'Quận Ninh Kiều, Cần Thơ',
                'chinh_sach_van_chuyen' => 'Giao hàng toàn quốc. Miễn phí vận chuyển cho đơn hàng từ 500.000 ₫.',
                'chinh_sach_doi_tra' => 'Hỗ trợ đổi trả trong 7 ngày nếu sản phẩm lỗi do nhà sản xuất.',
            ]
        );
    }
}
