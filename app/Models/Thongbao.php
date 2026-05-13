<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thongbao extends Model
{
    protected $table = 'thongbao';

    protected $fillable = [
        'tieu_de',
        'noi_dung',
        'loai',
        'duong_dan',
        'da_doc',
        'doc_luc',
        'donhang_id',
        'sanpham_id',
    ];

    protected $casts = [
        'da_doc' => 'boolean',
        'doc_luc' => 'datetime',
    ];

    public const LOAI_DON_HANG = 'don_hang';
    public const LOAI_TON_KHO = 'ton_kho';
    public const LOAI_HE_THONG = 'he_thong';

    public function donhang()
    {
        return $this->belongsTo(Donhang::class, 'donhang_id');
    }

    public function sanpham()
    {
        return $this->belongsTo(Sanpham::class, 'sanpham_id');
    }

    public function loaiText(): string
    {
        return match ($this->loai) {
            self::LOAI_DON_HANG => 'Đơn hàng',
            self::LOAI_TON_KHO => 'Tồn kho',
            self::LOAI_HE_THONG => 'Hệ thống',
            default => 'Khác',
        };
    }

    public function loaiClass(): string
    {
        return match ($this->loai) {
            self::LOAI_DON_HANG => 'thongbao-donhang',
            self::LOAI_TON_KHO => 'thongbao-tonkho',
            self::LOAI_HE_THONG => 'thongbao-hethong',
            default => 'thongbao-hethong',
        };
    }

    public function danhDauDaDoc(): void
    {
        if (!$this->da_doc) {
            $this->update([
                'da_doc' => true,
                'doc_luc' => now(),
            ]);
        }
    }
}
