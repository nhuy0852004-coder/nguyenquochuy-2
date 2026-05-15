<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Danhmuc extends Model
{
    use SoftDeletes;

    protected $table = 'danhmuc';

    protected $fillable = [
        'parent_id',
        'ten_danh_muc',
        'duong_dan',
        'mo_ta',
        'thu_tu',
        'trang_thai',
    ];

    protected $casts = [
        'trang_thai' => 'boolean',
        'thu_tu' => 'integer',
        'parent_id' => 'integer',
    ];

    public function sanpham()
    {
        return $this->hasMany(Sanpham::class, 'danhmuc_id');
    }

    public function cha()
    {
        return $this->belongsTo(Danhmuc::class, 'parent_id');
    }

    public function con()
    {
        return $this->hasMany(Danhmuc::class, 'parent_id')
            ->orderBy('thu_tu')
            ->orderBy('ten_danh_muc');
    }

    public function conDangBat()
    {
        return $this->hasMany(Danhmuc::class, 'parent_id')
            ->where('trang_thai', true)
            ->orderBy('thu_tu')
            ->orderBy('ten_danh_muc');
    }

    public function tatCaCon()
    {
        return $this->con()->with('tatCaCon');
    }

    public function laDanhMucGoc(): bool
    {
        return is_null($this->parent_id);
    }

    public function coDanhMucCon(): bool
    {
        return $this->con()->exists();
    }

    public function breadcrumb(): string
    {
        $items = [];
        $current = $this;

        while ($current) {
            array_unshift($items, $current->ten_danh_muc);
            $current = $current->cha;
        }

        return implode(' / ', $items);
    }

    public function layTatCaIdCon(): array
    {
        $ids = [];

        foreach ($this->con as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->layTatCaIdCon());
        }

        return $ids;
    }
}
