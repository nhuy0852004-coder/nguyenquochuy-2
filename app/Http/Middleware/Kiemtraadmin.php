<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Kiemtraadmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()
                ->route('admin.dangnhap')
                ->with('loi', 'Vui lòng đăng nhập để truy cập trang quản trị.');
        }

        if (!Auth::user()->trang_thai) {
            Auth::logout();

            return redirect()
                ->route('admin.dangnhap')
                ->with('loi', 'Tài khoản của bạn đã bị khóa.');
        }

        if (!Auth::user()->coQuyenVaoAdmin()) {
            abort(403, 'Bạn không có quyền truy cập trang quản trị.');
        }

        return $next($request);
    }
}
