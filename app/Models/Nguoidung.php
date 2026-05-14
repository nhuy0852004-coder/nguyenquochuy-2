<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Nguoidung extends Authenticatable
{
    use Notifiable;

    protected $table = 'nguoidung';

    protected $fillable = [
        'ho_ten',
        'email',
        'mat_khau',
        'vai_tro',
        'trang_thai',
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

    public function laAdmin(): bool
    {
        return $this->vai_tro === 'admin';
    }

    public function laNhanVien(): bool
    {
        return $this->vai_tro === 'nhan_vien';
    }

    public function coQuyenVaoAdmin(): bool
    {
        return in_array($this->vai_tro, ['admin', 'nhan_vien']);
    }

    public function coVaiTro(string|array $vaiTro): bool
    {
        if (is_array($vaiTro)) {
            return in_array($this->vai_tro, $vaiTro);
        }

        return $this->vai_tro === $vaiTro;
    }

    public function tenVaiTro(): string
    {
        return match ($this->vai_tro) {
            'admin' => 'Quản trị viên',
            'nhan_vien' => 'Nhân viên',
            default => 'Không xác định',
        };
    }
}
