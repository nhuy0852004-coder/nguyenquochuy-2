<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoimatkhauRequest;
use App\Http\Requests\NguoidungRequest;
use App\Models\Nguoidung;
use App\Services\NguoidungService;
use Illuminate\Http\Request;

class NguoidungController extends Controller
{
    public function __construct(
        private NguoidungService $nguoidungService
    ) {
        //
    }

    public function index(Request $request)
    {
        $tukhoa = $request->input('tu_khoa');

        $danhsachnguoidung = $this->nguoidungService->layDanhSach($tukhoa);

        return view('admin.nguoidung.index', compact(
            'danhsachnguoidung',
            'tukhoa'
        ));
    }

    public function store(NguoidungRequest $request)
    {
        $this->nguoidungService->taoNguoiDung(
            $request->validated(),
            $request->has('trang_thai')
        );

        return redirect()
            ->route('admin.nguoidung.index')
            ->with('thanhcong', 'Thêm người dùng thành công.');
    }

    public function update(NguoidungRequest $request, Nguoidung $nguoidung)
    {
        $this->nguoidungService->capNhatNguoiDung(
            $nguoidung,
            $request->validated(),
            $request->has('trang_thai')
        );

        return redirect()
            ->route('admin.nguoidung.index')
            ->with('thanhcong', 'Cập nhật người dùng thành công.');
    }

    public function doiMatKhau(DoimatkhauRequest $request, Nguoidung $nguoidung)
    {
        $this->nguoidungService->doiMatKhau(
            $nguoidung,
            $request->mat_khau
        );

        return redirect()
            ->route('admin.nguoidung.index')
            ->with('thanhcong', 'Đổi mật khẩu thành công.');
    }

    public function doiTrangThai(Nguoidung $nguoidung)
    {
        try {
            $this->nguoidungService->doiTrangThai($nguoidung);

            return redirect()
                ->route('admin.nguoidung.index')
                ->with('thanhcong', 'Cập nhật trạng thái tài khoản thành công.');
        } catch (\Exception $exception) {
            return redirect()
                ->route('admin.nguoidung.index')
                ->with('loi', $exception->getMessage());
        }
    }

    public function destroy(Nguoidung $nguoidung)
    {
        try {
            $this->nguoidungService->xoaNguoiDung($nguoidung);

            return redirect()
                ->route('admin.nguoidung.index')
                ->with('thanhcong', 'Xóa người dùng thành công.');
        } catch (\Exception $exception) {
            return redirect()
                ->route('admin.nguoidung.index')
                ->with('loi', $exception->getMessage());
        }
    }
}
