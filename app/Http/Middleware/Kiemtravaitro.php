<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Kiemtravaitro
{
    public function handle(Request $request, Closure $next, ...$vaitro): Response
    {
        if (!Auth::check()) {
            return redirect()
                ->route('admin.dangnhap')
                ->with('loi', 'Vui lòng đăng nhập để tiếp tục.');
        }

        if (!Auth::user()->coVaiTro($vaitro)) {
            abort(403, 'Bạn không có quyền thực hiện chức năng này.');
        }

        return $next($request);
    }
}
