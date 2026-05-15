<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThanhtoanRequest;
use App\Models\Donhang;
use App\Services\GiohangService;
use App\Services\ThanhtoanService;
use App\Services\VnpayService;
use Illuminate\Http\Request;

class ThanhtoanController extends Controller
{
    public function __construct(
        private GiohangService $giohangService,
        private ThanhtoanService $thanhtoanService,
        private VnpayService $vnpayService
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

            if ($donhang->phuong_thuc_thanh_toan === 'vnpay') {
                return redirect()->away(
                    $this->vnpayService->taoUrlThanhToan($donhang)
                );
            }

            return redirect()
                ->route('web.thanhtoan.thanhcong', $donhang->ma_don_hang)
                ->with('thanhcong', 'Đặt hàng thành công.');
        } catch (\Exception $exception) {
            return redirect()
                ->route('web.giohang.index')
                ->with('loi', $exception->getMessage());
        }
    }

    public function vnpayKetQua(Request $request)
    {
        $duLieu = $request->all();

        if (!$this->vnpayService->xacThucPhanHoi($duLieu)) {
            return redirect()
                ->route('web.thanhtoan.thatbai')
                ->with('loi', 'Không thể xác thực kết quả thanh toán.');
        }

        $donhang = Donhang::query()
            ->where('ma_don_hang', $duLieu['vnp_TxnRef'] ?? null)
            ->firstOrFail();

        if ($this->vnpayService->thanhCong($duLieu)) {
            $donhang->update([
                'trang_thai_thanh_toan' => 'da_thanh_toan',
                'ma_giao_dich_thanh_toan' => $duLieu['vnp_TransactionNo'] ?? null,
                'ma_phan_hoi_thanh_toan' => $duLieu['vnp_ResponseCode'] ?? null,
                'cong_thanh_toan' => 'vnpay',
                'thanh_toan_luc' => now(),
            ]);

            cache()->forget('dashboard_thong_ke');

            return redirect()
                ->route('web.thanhtoan.thanhcong', $donhang->ma_don_hang)
                ->with('thanhcong', 'Thanh toán VNPay thành công.');
        }

        $donhang->update([
            'trang_thai_thanh_toan' => 'thanh_toan_that_bai',
            'ma_phan_hoi_thanh_toan' => $duLieu['vnp_ResponseCode'] ?? null,
            'cong_thanh_toan' => 'vnpay',
        ]);

        return redirect()
            ->route('web.thanhtoan.thatbai')
            ->with('loi', 'Thanh toán VNPay không thành công hoặc đã bị hủy.');
    }

    public function thatBai()
    {
        return view('web.thanhtoan.thatbai');
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
