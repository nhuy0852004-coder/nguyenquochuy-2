<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThanhtoanRequest;
use App\Models\Donhang;
use App\Services\GiohangService;
use App\Services\ThanhtoanService;

class ThanhtoanController extends Controller
{
    public function __construct(
        private GiohangService $giohangService,
        private ThanhtoanService $thanhtoanService
    ) {
        //
    }

    public function index()
    {
        $giohang = $this->giohangService->layGioHang();

        if (empty($giohang)) {
            return redirect()
                ->route('web.giohang.index')
                ->with('loi', 'Giỏ hàng đang trống, vui lòng thêm sản phẩm trước khi thanh toán.');
        }

        $tamTinh = $this->giohangService->tinhTongTien($giohang);
        $phiVanChuyen = $this->thanhtoanService->tinhPhiVanChuyen($tamTinh);
        $tongTien = $tamTinh + $phiVanChuyen;

        return view('web.thanhtoan.index', compact(
            'giohang',
            'tamTinh',
            'phiVanChuyen',
            'tongTien'
        ));
    }

    public function datHang(ThanhtoanRequest $request)
    {
        try {
            $giohang = $this->giohangService->layGioHang();

            $donhang = $this->thanhtoanService->datHang(
                $request->validated(),
                $giohang
            );

            return redirect()
                ->route('web.thanhtoan.thanhcong', $donhang->ma_don_hang)
                ->with('thanhcong', 'Đặt hàng thành công.');
        } catch (\Exception $exception) {
            return redirect()
                ->route('web.giohang.index')
                ->with('loi', $exception->getMessage());
        }
    }

    public function thanhCong(string $madonhang)
    {
        $donhang = Donhang::query()
            ->with('chitietdonhang')
            ->where('ma_don_hang', $madonhang)
            ->firstOrFail();

        return view('web.thanhtoan.thanhcong', compact('donhang'));
    }
}
