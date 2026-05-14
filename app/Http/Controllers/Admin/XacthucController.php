<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nguoidung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class XacthucController extends Controller
{
    public function hienThiDangNhap()
    {
        if (Auth::check()) {
            return redirect()->route('admin.bangdieukhien');
        }

        return view('admin.xacthuc.dangnhap');
    }

    public function dangNhap(Request $request)
    {
        $request->validate(
            [
                'email' => ['required', 'email'],
                'mat_khau' => ['required', 'string', 'min:6'],
            ],
            [
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email không đúng định dạng.',
                'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
                'mat_khau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            ]
        );

        $nguoidung = Nguoidung::query()
            ->where('email', $request->email)
            ->first();

        if (!$nguoidung) {
            return back()
                ->withInput()
                ->with('loi', 'Email hoặc mật khẩu không đúng.');
        }

        if (!$nguoidung->trang_thai) {
            return back()
                ->withInput()
                ->with('loi', 'Tài khoản đã bị khóa.');
        }

        if (!$nguoidung->laAdmin()) {
            return back()
                ->withInput()
                ->with('loi', 'Tài khoản không có quyền truy cập Admin.');
        }

        if (!Hash::check($request->mat_khau, $nguoidung->mat_khau)) {
            return back()
                ->withInput()
                ->with('loi', 'Email hoặc mật khẩu không đúng.');
        }

        Auth::login($nguoidung, $request->boolean('ghi_nho'));

        $request->session()->regenerate();

        return redirect()
            ->intended(route('admin.bangdieukhien'))
            ->with('thanhcong', 'Đăng nhập thành công.');
    }

    public function dangXuat(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.dangnhap')
            ->with('thanhcong', 'Đăng xuất thành công.');
    }
}
