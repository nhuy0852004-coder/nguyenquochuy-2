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
}
