<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nguoidung;
use App\Models\Nhatkyhoatdong;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NhatkyhoatdongController extends Controller
{
    public function index(Request $request)
    {
        $nguoidungId = $request->input('nguoidung_id');
        $hanhdong = $request->input('hanh_dong');
        $tuNgay = $request->input('tu_ngay');
        $denNgay = $request->input('den_ngay');

        $danhsachnguoidung = Nguoidung::query()
            ->orderBy('ho_ten')
            ->get();

        $danhsachhanhdong = [
            Nhatkyhoatdong::HANH_DONG_DANG_NHAP => 'Đăng nhập',
            Nhatkyhoatdong::HANH_DONG_DANG_XUAT => 'Đăng xuất',
            Nhatkyhoatdong::HANH_DONG_THEM => 'Thêm mới',
            Nhatkyhoatdong::HANH_DONG_SUA => 'Cập nhật',
            Nhatkyhoatdong::HANH_DONG_XOA => 'Xóa',
            Nhatkyhoatdong::HANH_DONG_DOI_TRANG_THAI => 'Đổi trạng thái',
            Nhatkyhoatdong::HANH_DONG_DOI_MAT_KHAU => 'Đổi mật khẩu',
        ];

        $danhsachnhatky = Nhatkyhoatdong::query()
            ->with('nguoidung')
            ->when($nguoidungId, function ($query) use ($nguoidungId) {
                $query->where('nguoidung_id', $nguoidungId);
            })
            ->when($hanhdong, function ($query) use ($hanhdong) {
                $query->where('hanh_dong', $hanhdong);
            })
            ->when($tuNgay, function ($query) use ($tuNgay) {
                $query->where('created_at', '>=', Carbon::parse($tuNgay)->startOfDay());
            })
            ->when($denNgay, function ($query) use ($denNgay) {
                $query->where('created_at', '<=', Carbon::parse($denNgay)->endOfDay());
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.nhatkyhoatdong.index', compact(
            'danhsachnhatky',
            'danhsachnguoidung',
            'danhsachhanhdong',
            'nguoidungId',
            'hanhdong',
            'tuNgay',
            'denNgay'
        ));
    }
}
