<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Danhmuc extends Model
{
    use SoftDeletes;

    protected $table = 'danhmuc';

    protected $fillable = [
        'ten_danh_muc',
        'duong_dan',
        'mo_ta',
        'thu_tu',
        'trang_thai',
    ];

    protected $casts = [
        'trang_thai' => 'boolean',
        'thu_tu' => 'integer',
    ];
}
