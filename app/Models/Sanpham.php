<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sanpham extends Model
{
    use SoftDeletes;

    protected $table = 'sanpham';

    protected $fillable = [
        'danhmuc_id',
        'ten_san_pham',
        'duong_dan',
        'ma_san_pham',
        'gia_ban',
        'gia_khuyen_mai',
        'so_luong_ton',
        'muc_canh_bao_ton',
        'anh_dai_dien',
        'mo_ta_ngan',
        'mo_ta_chi_tiet',
        'trang_thai',
        'noi_bat',
    ];

    protected $casts = [
        'gia_ban' => 'integer',
        'gia_khuyen_mai' => 'integer',
        'so_luong_ton' => 'integer',
        'muc_canh_bao_ton' => 'integer',
        'trang_thai' => 'boolean',
        'noi_bat' => 'boolean',
    ];

    public function danhmuc()
    {
        return $this->belongsTo(Danhmuc::class, 'danhmuc_id');
    }

    public function anhsanpham()
    {
        return $this->hasMany(Anhsanpham::class, 'sanpham_id');
    }

    public function chitietdonhang()
    {
        return $this->hasMany(Chitietdonhang::class, 'sanpham_id');
    }

    public function giaHienTai(): int
    {
        return $this->gia_khuyen_mai ?: $this->gia_ban;
    }

    public function ganHetHang(): bool
    {
        return $this->so_luong_ton <= $this->muc_canh_bao_ton;
    }

    public function hetHang(): bool
    {
        return $this->so_luong_ton <= 0;
    }
}
