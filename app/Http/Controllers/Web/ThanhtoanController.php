<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThanhtoanRequest;
use App\Models\Chitietdonhang;
use App\Models\Donhang;
use App\Models\Khachhang;
use App\Models\Sanpham;
use Illuminate\Support\Facades\DB;

class ThanhtoanController extends Controller
{
    public function index()
    {
        $giohang = session()->get('giohang', []);

        if (empty($giohang)) {
            return redirect()
                ->route('web.giohang.index')
                ->with('loi', 'Giỏ hàng đang trống, vui lòng thêm sản phẩm trước khi thanh toán.');
        }

        $tamTinh = collect($giohang)->sum('thanh_tien');
        $phiVanChuyen = $this->tinhPhiVanChuyen($tamTinh);
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
        $giohang = session()->get('giohang', []);

        if (empty($giohang)) {
            return redirect()
                ->route('web.giohang.index')
                ->with('loi', 'Giỏ hàng đang trống.');
        }

        try {
            $donhang = DB::transaction(function () use ($request, $giohang) {
                $this->kiemTraTonKho($giohang);

                $tamTinh = collect($giohang)->sum('thanh_tien');
                $phiVanChuyen = $this->tinhPhiVanChuyen($tamTinh);
                $tongTien = $tamTinh + $phiVanChuyen;

                $khachhang = Khachhang::updateOrCreate(
                    [
                        'so_dien_thoai' => $request->so_dien_thoai,
                    ],
                    [
                        'ho_ten' => $request->ho_ten,
                        'email' => $request->email,
                        'dia_chi' => $request->dia_chi,
                    ]
                );

                $donhang = Donhang::create([
                    'khachhang_id' => $khachhang->id,
                    'ma_don_hang' => $this->taoMaDonHang(),
                    'ho_ten_nguoi_nhan' => $request->ho_ten,
                    'so_dien_thoai_nguoi_nhan' => $request->so_dien_thoai,
                    'email_nguoi_nhan' => $request->email,
                    'dia_chi_giao_hang' => $request->dia_chi,
                    'ghi_chu' => $request->ghi_chu,
                    'tam_tinh' => $tamTinh,
                    'phi_van_chuyen' => $phiVanChuyen,
                    'tong_tien' => $tongTien,
                    'phuong_thuc_thanh_toan' => $request->phuong_thuc_thanh_toan,
                    'trang_thai_thanh_toan' => 'chua_thanh_toan',
                    'trang_thai_don_hang' => 'cho_xac_nhan',
                ]);

                foreach ($giohang as $item) {
                    $sanpham = Sanpham::lockForUpdate()->findOrFail($item['sanpham_id']);

                    if ($sanpham->so_luong_ton < $item['so_luong']) {
                        throw new \Exception('Sản phẩm ' . $sanpham->ten_san_pham . ' không đủ tồn kho.');
                    }

                    Chitietdonhang::create([
                        'donhang_id' => $donhang->id,
                        'sanpham_id' => $sanpham->id,
                        'ten_san_pham' => $sanpham->ten_san_pham,
                        'ma_san_pham' => $sanpham->ma_san_pham,
                        'anh_san_pham' => $sanpham->anh_dai_dien,
                        'don_gia' => $item['don_gia'],
                        'so_luong' => $item['so_luong'],
                        'thanh_tien' => $item['thanh_tien'],
                    ]);

                    $sanpham->decrement('so_luong_ton', $item['so_luong']);
                }

                return $donhang;
            });

            session()->forget('giohang');

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

    private function kiemTraTonKho(array $giohang): void
    {
        foreach ($giohang as $item) {
            $sanpham = Sanpham::find($item['sanpham_id']);

            if (!$sanpham || !$sanpham->trang_thai) {
                throw new \Exception('Một sản phẩm trong giỏ hàng không còn khả dụng.');
            }

            if ($sanpham->so_luong_ton < $item['so_luong']) {
                throw new \Exception('Sản phẩm ' . $sanpham->ten_san_pham . ' không đủ tồn kho.');
            }
        }
    }

    private function tinhPhiVanChuyen(int $tamTinh): int
    {
        if ($tamTinh >= 500000) {
            return 0;
        }

        return 30000;
    }

    private function taoMaDonHang(): string
    {
        do {
            $ma = 'DH' . now()->format('ymdHis') . random_int(10, 99);
        } while (Donhang::where('ma_don_hang', $ma)->exists());

        return $ma;
    }
}
