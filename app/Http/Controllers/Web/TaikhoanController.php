<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TaikhoanController extends Controller
{
    public function index()
    {
        $khachhang = Auth::guard('khachhang')->user();

        $khachhang->load([
            'donhang' => function ($query) {
                $query->orderByDesc('id')->limit(10);
            },
            'donhang.chitietdonhang',
        ]);

        return view('web.taikhoan.index', compact('khachhang'));
    }
}
