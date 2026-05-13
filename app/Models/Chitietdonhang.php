<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chitietdonhang extends Model
{
    protected $table = 'chitietdonhang';

    protected $fillable = [
        'donhang_id',
        'sanpham_id',
        'ten_san_pham',
        'ma_san_pham',
        'anh_san_pham',
        'don_gia',
        'so_luong',
        'thanh_tien',
    ];

    protected $casts = [
        'don_gia' => 'integer',
        'so_luong' => 'integer',
        'thanh_tien' => 'integer',
    ];

    public function donhang()
    {
        return $this->belongsTo(Donhang::class, 'donhang_id');
    }

    public function sanpham()
    {
        return $this->belongsTo(Sanpham::class, 'sanpham_id');
    }
}
