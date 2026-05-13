<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donhang;
use App\Services\ThongbaoService;
use Illuminate\Http\Request;
use App\Events\CapnhattrangthaidonhangEvent;

class DonhangController extends Controller
{
    public function index(Request $request)
    {
        $tukhoa = $request->input('tu_khoa');
        $trangthai = $request->input('trang_thai');

        $danhsachtrangthai = Donhang::danhSachTrangThai();

        $danhsachdonhang = Donhang::query()
            ->with('khachhang')
            ->when($tukhoa, function ($query) use ($tukhoa) {
                $query->where(function ($q) use ($tukhoa) {
                    $q->where('ma_don_hang', 'like', '%' . $tukhoa . '%')
                        ->orWhere('ho_ten_nguoi_nhan', 'like', '%' . $tukhoa . '%')
                        ->orWhere('so_dien_thoai_nguoi_nhan', 'like', '%' . $tukhoa . '%')
                        ->orWhere('email_nguoi_nhan', 'like', '%' . $tukhoa . '%');
                });
            })
            ->when($trangthai, function ($query) use ($trangthai) {
                $query->where('trang_thai_don_hang', $trangthai);
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.donhang.index', compact(
            'danhsachdonhang',
            'danhsachtrangthai',
            'tukhoa',
            'trangthai'
        ));
    }

    public function chitiet(Donhang $donhang)
    {
        $donhang->load(['khachhang', 'chitietdonhang.sanpham']);

        $danhsachtrangthai = Donhang::danhSachTrangThai();

        return view('admin.donhang.chitiet', compact(
            'donhang',
            'danhsachtrangthai'
        ));
    }

    public function capnhattrangthai(Request $request, Donhang $donhang)
    {
        $request->validate(
            [
                'trang_thai_don_hang' => [
                    'required',
                    'in:' . implode(',', array_keys(Donhang::danhSachTrangThai())),
                ],
            ],
            [
                'trang_thai_don_hang.required' => 'Vui lòng chọn trạng thái đơn hàng.',
                'trang_thai_don_hang.in' => 'Trạng thái đơn hàng không hợp lệ.',
            ]
        );

        $trangThaiCu = $donhang->trang_thai_don_hang;
        $trangThaiMoi = $request->trang_thai_don_hang;

        if ($trangThaiCu === Donhang::TRANG_THAI_HOAN_THANH && $trangThaiMoi === Donhang::TRANG_THAI_DA_HUY) {
            return back()->with('loi', 'Không thể hủy đơn hàng đã hoàn thành.');
        }

        $donhang->update([
            'trang_thai_don_hang' => $trangThaiMoi,
        ]);

        $donhang->refresh();

        if ($trangThaiMoi === Donhang::TRANG_THAI_DA_HUY && $trangThaiCu !== Donhang::TRANG_THAI_DA_HUY) {
            app(ThongbaoService::class)->taoThongBaoDonHangBiHuy($donhang);
        }

        try {
            broadcast(new CapnhattrangthaidonhangEvent($donhang));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('admin.donhang.chitiet', $donhang)
            ->with('thanhcong', 'Cập nhật trạng thái đơn hàng thành công.');
    }
}
