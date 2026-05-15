<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Khachhang extends Authenticatable
{
    use Notifiable;

    protected $table = 'khachhang';

    protected $fillable = [
        'ho_ten',
        'so_dien_thoai',
        'email',
        'mat_khau',
        'trang_thai',
        'dia_chi',
        'google_id',
        'facebook_id',
        'anh_dai_dien',
    ];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    protected $casts = [
        'trang_thai' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    public function donhang()
    {
        return $this->hasMany(Donhang::class, 'khachhang_id');
    }

    public function coTaiKhoan(): bool
    {
        return !empty($this->mat_khau);
    }

    public function tongSoDon(): int
    {
        return $this->donhang()->count();
    }

    public function tongTienDaMua(): int
    {
        return (int) $this->donhang()
            ->where('trang_thai_don_hang', '!=', Donhang::TRANG_THAI_DA_HUY)
            ->sum('tong_tien');
    }
}
