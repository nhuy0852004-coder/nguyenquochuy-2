<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThanhtoanRequest;
use App\Models\Donhang;
use App\Services\GiohangService;
use App\Services\PayosService;
use App\Services\ThanhtoanService;
use App\Services\VnpayService;
use Illuminate\Http\Request;

class ThanhtoanController extends Controller
{
    public function __construct(
        private GiohangService $giohangService,
        private ThanhtoanService $thanhtoanService,
        private PayosService $payosService
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

            if ($donhang->phuong_thuc_thanh_toan === 'payos') {
                $donhang->load('chitietdonhang');

                $ketquaPayos = $this->payosService->taoLinkThanhToan($donhang);

                $donhang->update([
                    'ma_thanh_toan_payos' => $ketquaPayos['order_code'],
                    'link_thanh_toan' => $ketquaPayos['checkout_url'],
                    'ma_giao_dich_thanh_toan' => $ketquaPayos['payment_link_id'],
                    'cong_thanh_toan' => 'payos',
                    'trang_thai_thanh_toan' => 'cho_thanh_toan',
                    'trang_thai_don_hang' => \App\Models\Donhang::TRANG_THAI_CHO_XAC_NHAN,
                ]);

                return redirect()->away($ketquaPayos['checkout_url']);
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

    public function payosKetQua(Request $request)
    {
        $orderCode = $request->input('orderCode');
        $status = $request->input('status');
        $code = $request->input('code');

        $donhang = Donhang::query()
            ->where('ma_thanh_toan_payos', $orderCode)
            ->first();

        if (!$donhang) {
            return redirect()
                ->route('web.thanhtoan.thatbai')
                ->with('loi', 'Không tìm thấy đơn hàng thanh toán payOS.');
        }

        if (in_array(strtoupper((string) $status), ['PAID', 'SUCCESS'], true) || $code === '00') {
            $donhang->update([
                'trang_thai_thanh_toan' => 'da_thanh_toan',
                'trang_thai_don_hang' => \App\Models\Donhang::TRANG_THAI_DA_XAC_NHAN,
                'ma_phan_hoi_thanh_toan' => $code ?: $status,
                'cong_thanh_toan' => 'payos',
                'thanh_toan_luc' => now(),
            ]);

            cache()->forget('dashboard_thong_ke');
        }

        return redirect()
            ->route('web.theodoi.chitiet', $donhang->ma_don_hang)
            ->with('thanhcong', 'Thanh toán payOS đã được ghi nhận. Hệ thống sẽ đồng bộ trạng thái nếu cần.');
    }

    public function payosHuy(Request $request)
    {
        $orderCode = $request->input('orderCode');

        $donhang = Donhang::query()
            ->where('ma_thanh_toan_payos', $orderCode)
            ->first();

        if ($donhang) {
            $donhang->update([
                'trang_thai_thanh_toan' => 'thanh_toan_that_bai',
                'ma_phan_hoi_thanh_toan' => 'cancel',
            ]);
        }

        return redirect()
            ->route('web.thanhtoan.thatbai')
            ->with('loi', 'Bạn đã hủy thanh toán payOS.');
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
