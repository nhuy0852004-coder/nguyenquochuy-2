<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Khachhang extends Model
{
    protected $table = 'khachhang';

    protected $fillable = [
        'ho_ten',
        'so_dien_thoai',
        'email',
        'dia_chi',
    ];

    public function donhang()
    {
        return $this->hasMany(Donhang::class, 'khachhang_id');
    }

    public function tongSoDon(): int
    {
        return $this->donhang()->count();
    }

    public function tongSoDonKhongHuy(): int
    {
        return $this->donhang()
            ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY)
            ->count();
    }

    public function tongTienDaMua(): int
    {
        return (int) $this->donhang()
            ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY)
            ->sum('tong_tien');
    }

    public function lanMuaGanNhat()
    {
        return $this->donhang()
            ->latest('created_at')
            ->first();
    }
}
