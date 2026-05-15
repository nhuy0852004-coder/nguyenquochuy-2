<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\DangkykhachhangRequest;
use App\Models\Khachhang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class XacthuckhachhangController extends Controller
{
    public function hienThiDangNhap()
    {
        if (Auth::guard('khachhang')->check()) {
            return redirect()->route('web.taikhoan.index');
        }

        return view('web.xacthuc.dangnhap');
    }

    public function dangNhap(Request $request)
    {
        $request->validate(
            [
                'tai_khoan' => ['required', 'string'],
                'mat_khau' => ['required', 'string', 'min:8'],
            ],
            [
                'tai_khoan.required' => 'Vui lòng nhập email hoặc số điện thoại.',
                'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
                'mat_khau.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            ]
        );

        $taiKhoan = trim($request->tai_khoan);

        $khachhang = Khachhang::query()
            ->where('email', $taiKhoan)
            ->orWhere('so_dien_thoai', $taiKhoan)
            ->first();

        if (!$khachhang || !$khachhang->mat_khau) {
            return back()
                ->withInput()
                ->with('loi', 'Tài khoản hoặc mật khẩu không đúng.');
        }

        if (!$khachhang->trang_thai) {
            return back()
                ->withInput()
                ->with('loi', 'Tài khoản của bạn đã bị khóa.');
        }

        if (!Hash::check($request->mat_khau, $khachhang->mat_khau)) {
            return back()
                ->withInput()
                ->with('loi', 'Tài khoản hoặc mật khẩu không đúng.');
        }

        Auth::guard('khachhang')->login($khachhang, $request->boolean('ghi_nho'));

        $request->session()->regenerate();

        return redirect()
            ->intended(route('web.taikhoan.index'))
            ->with('thanhcong', 'Đăng nhập thành công.');
    }

    public function hienThiDangKy()
    {
        if (Auth::guard('khachhang')->check()) {
            return redirect()->route('web.taikhoan.index');
        }

        return view('web.xacthuc.dangky');
    }

    public function dangKy(DangkykhachhangRequest $request)
    {
        $dulieu = $request->validated();

        $khachhang = Khachhang::create([
            'ho_ten' => $dulieu['ho_ten'],
            'so_dien_thoai' => $dulieu['so_dien_thoai'],
            'email' => $dulieu['email'] ?? null,
            'mat_khau' => Hash::make($dulieu['mat_khau']),
            'trang_thai' => true,
        ]);

        Auth::guard('khachhang')->login($khachhang);

        $request->session()->regenerate();

        return redirect()
            ->route('web.taikhoan.index')
            ->with('thanhcong', 'Đăng ký tài khoản thành công.');
    }

    public function dangNhapGoogle(Request $request)
    {
        $request->session()->put('url.intended', url()->previous());

        return Socialite::driver('google')->stateless()->redirect();
    }

    public function dangNhapGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (Throwable $exception) {
            return redirect()
                ->route('web.dangnhap')
                ->with('loi', 'Không thể xác thực với Google. Vui lòng thử lại.');
        }

        if (!filled($googleUser->getEmail())) {
            return redirect()
                ->route('web.dangnhap')
                ->with('loi', 'Tài khoản Google không cung cấp email.');
        }

        $khachhang = Khachhang::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($khachhang) {
            $khachhang->update([
                'google_id' => $khachhang->google_id ?: $googleUser->getId(),
                'ho_ten' => $googleUser->getName() ?: $khachhang->ho_ten,
                'anh_dai_dien' => $googleUser->getAvatar() ?: $khachhang->anh_dai_dien,
                'trang_thai' => true,
            ]);
        } else {
            $khachhang = Khachhang::create([
                'ho_ten' => $googleUser->getName() ?: 'Khách hàng Google',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'anh_dai_dien' => $googleUser->getAvatar(),
                'trang_thai' => true,
            ]);
        }

        Auth::guard('khachhang')->login($khachhang, true);
        $request->session()->regenerate();

        return redirect()
            ->intended(route('web.taikhoan.index'))
            ->with('thanhcong', 'Đăng nhập Google thành công.');
    }

    public function dangXuat(Request $request)
    {
        Auth::guard('khachhang')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('web.trangchu')
            ->with('thanhcong', 'Đăng xuất thành công.');
    }
}
