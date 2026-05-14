<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Sanpham;
use App\Services\GiohangService;
use Illuminate\Http\Request;

class GiohangController extends Controller
{
    public function __construct(
        private GiohangService $giohangService
    ) {
        //
    }

    public function index()
    {
        $giohang = $this->giohangService->layGioHang();
        $tongtien = $this->giohangService->tinhTongTien($giohang);

        return view('web.giohang.index', compact('giohang', 'tongtien'));
    }

    public function them(Request $request, Sanpham $sanpham)
    {
        try {
            $soLuong = max(1, (int) $request->input('so_luong', 1));

            $giohang = $this->giohangService->themSanPham($sanpham, $soLuong);

            $soLuongGioHang = $this->giohangService->tinhSoLuong($giohang);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã thêm sản phẩm vào giỏ hàng.',
                    'so_luong_gio_hang' => $soLuongGioHang,
                ]);
            }

            if ($request->input('mua_ngay')) {
                return redirect()
                    ->route('web.thanhtoan.index')
                    ->with('thanhcong', 'Sản phẩm đã được thêm vào giỏ hàng. Vui lòng hoàn tất thanh toán.');
            }

            return redirect()
                ->route('web.giohang.index')
                ->with('thanhcong', 'Đã thêm sản phẩm vào giỏ hàng.');
        } catch (\Exception $exception) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return back()->with('loi', $exception->getMessage());
        }
    }

    public function capnhat(Request $request)
    {
        $this->giohangService->capNhatSoLuong(
            $request->input('so_luong', [])
        );

        return redirect()
            ->route('web.giohang.index')
            ->with('thanhcong', 'Cập nhật giỏ hàng thành công.');
    }

    public function xoa(Sanpham $sanpham)
    {
        $this->giohangService->xoaSanPham($sanpham);

        return redirect()
            ->route('web.giohang.index')
            ->with('thanhcong', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function xoaTatCa()
    {
        $this->giohangService->xoaTatCa();

        return redirect()
            ->route('web.giohang.index')
            ->with('thanhcong', 'Đã xóa toàn bộ giỏ hàng.');
    }
}
