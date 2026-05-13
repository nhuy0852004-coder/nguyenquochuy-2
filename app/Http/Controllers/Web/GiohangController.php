<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Sanpham;
use Illuminate\Http\Request;

class GiohangController extends Controller
{
    public function index()
    {
        $giohang = session()->get('giohang', []);
        $tongtien = $this->tinhTongTien($giohang);

        return view('web.giohang.index', compact('giohang', 'tongtien'));
    }

    public function them(Request $request, Sanpham $sanpham)
    {
        if (!$sanpham->trang_thai) {
            return back()->with('loi', 'Sản phẩm hiện không còn hiển thị.');
        }

        if ($sanpham->so_luong_ton <= 0) {
            return back()->with('loi', 'Sản phẩm đã hết hàng.');
        }

        $soLuong = max(1, (int) $request->input('so_luong', 1));

        if ($soLuong > $sanpham->so_luong_ton) {
            return back()->with('loi', 'Số lượng vượt quá tồn kho hiện tại.');
        }

        $giohang = session()->get('giohang', []);
        $id = $sanpham->id;

        if (isset($giohang[$id])) {
            $soLuongMoi = $giohang[$id]['so_luong'] + $soLuong;

            if ($soLuongMoi > $sanpham->so_luong_ton) {
                return back()->with('loi', 'Số lượng trong giỏ vượt quá tồn kho.');
            }

            $giohang[$id]['so_luong'] = $soLuongMoi;
            $giohang[$id]['thanh_tien'] = $giohang[$id]['so_luong'] * $giohang[$id]['don_gia'];
        } else {
            $donGia = $sanpham->giaHienTai();

            $giohang[$id] = [
                'sanpham_id' => $sanpham->id,
                'ten_san_pham' => $sanpham->ten_san_pham,
                'ma_san_pham' => $sanpham->ma_san_pham,
                'duong_dan' => $sanpham->duong_dan,
                'anh_dai_dien' => $sanpham->anh_dai_dien,
                'don_gia' => $donGia,
                'so_luong' => $soLuong,
                'ton_kho' => $sanpham->so_luong_ton,
                'thanh_tien' => $donGia * $soLuong,
            ];
        }

        session()->put('giohang', $giohang);

        $soLuongGioHang = collect($giohang)->sum('so_luong');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng.',
                'so_luong_gio_hang' => $soLuongGioHang,
            ]);
        }

        return redirect()
            ->route('web.giohang.index')
            ->with('thanhcong', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function capnhat(Request $request)
    {
        $giohang = session()->get('giohang', []);
        $soluong = $request->input('so_luong', []);

        foreach ($soluong as $sanphamId => $soLuongMoi) {
            if (!isset($giohang[$sanphamId])) {
                continue;
            }

            $sanpham = Sanpham::find($sanphamId);

            if (!$sanpham) {
                unset($giohang[$sanphamId]);
                continue;
            }

            $soLuongMoi = max(1, (int) $soLuongMoi);

            if ($soLuongMoi > $sanpham->so_luong_ton) {
                $soLuongMoi = $sanpham->so_luong_ton;
            }

            if ($soLuongMoi <= 0) {
                unset($giohang[$sanphamId]);
                continue;
            }

            $giohang[$sanphamId]['so_luong'] = $soLuongMoi;
            $giohang[$sanphamId]['ton_kho'] = $sanpham->so_luong_ton;
            $giohang[$sanphamId]['don_gia'] = $sanpham->giaHienTai();
            $giohang[$sanphamId]['thanh_tien'] = $giohang[$sanphamId]['don_gia'] * $soLuongMoi;
        }

        session()->put('giohang', $giohang);

        return redirect()
            ->route('web.giohang.index')
            ->with('thanhcong', 'Cập nhật giỏ hàng thành công.');
    }

    public function xoa(Sanpham $sanpham)
    {
        $giohang = session()->get('giohang', []);

        unset($giohang[$sanpham->id]);

        session()->put('giohang', $giohang);

        return redirect()
            ->route('web.giohang.index')
            ->with('thanhcong', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function xoaTatCa()
    {
        session()->forget('giohang');

        return redirect()
            ->route('web.giohang.index')
            ->with('thanhcong', 'Đã xóa toàn bộ giỏ hàng.');
    }

    private function tinhTongTien(array $giohang): int
    {
        return collect($giohang)->sum('thanh_tien');
    }
}
