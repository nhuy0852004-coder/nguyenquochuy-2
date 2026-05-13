<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donhang;
use App\Services\DonhangService;
use Illuminate\Http\Request;

class DonhangController extends Controller
{
    public function __construct(
        private DonhangService $donhangService
    ) {
        //
    }

    public function index(Request $request)
    {
        $tukhoa = $request->input('tu_khoa');
        $trangthai = $request->input('trang_thai');

        $danhsachtrangthai = Donhang::danhSachTrangThai();

        $danhsachdonhang = $this->donhangService->layDanhSachAdmin(
            $tukhoa,
            $trangthai
        );

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

        try {
            $this->donhangService->capNhatTrangThai(
                $donhang,
                $request->trang_thai_don_hang
            );

            return redirect()
                ->route('admin.donhang.chitiet', $donhang)
                ->with('thanhcong', 'Cập nhật trạng thái đơn hàng thành công.');
        } catch (\Exception $exception) {
            return redirect()
                ->route('admin.donhang.chitiet', $donhang)
                ->with('loi', $exception->getMessage());
        }
    }
}