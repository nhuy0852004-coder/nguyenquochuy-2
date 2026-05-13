<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anhsanpham extends Model
{
    protected $table = 'anhsanpham';

    protected $fillable = [
        'sanpham_id',
        'duong_dan_anh',
        'thu_tu',
    ];

    protected $casts = [
        'thu_tu' => 'integer',
    ];

    public function sanpham()
    {
        return $this->belongsTo(Sanpham::class, 'sanpham_id');
    }
}
