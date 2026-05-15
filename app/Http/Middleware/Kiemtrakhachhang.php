<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Kiemtrakhachhang
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('khachhang')->check()) {
            return redirect()
                ->route('web.dangnhap')
                ->with('loi', 'Vui lòng đăng nhập để sử dụng chức năng này.');
        }

        if (!Auth::guard('khachhang')->user()->trang_thai) {
            Auth::guard('khachhang')->logout();

            return redirect()
                ->route('web.dangnhap')
                ->with('loi', 'Tài khoản của bạn đã bị khóa.');
        }

        return $next($request);
    }
}
