<?php

namespace App\Services;

use App\Models\Sanpham;

class GiohangService
{
    public function layGioHang(): array
    {
        return session()->get('giohang', []);
    }

    public function tinhTongTien(array $giohang): int
    {
        return collect($giohang)->sum('thanh_tien');
    }

    public function tinhSoLuong(array $giohang): int
    {
        return collect($giohang)->sum('so_luong');
    }

    public function themSanPham(Sanpham $sanpham, int $soLuong = 1): array
    {
        if (!$sanpham->trang_thai) {
            throw new \Exception('Sản phẩm hiện không còn hiển thị.');
        }

        if ($sanpham->so_luong_ton <= 0) {
            throw new \Exception('Sản phẩm đã hết hàng.');
        }

        $soLuong = max(1, $soLuong);

        if ($soLuong > $sanpham->so_luong_ton) {
            throw new \Exception('Số lượng vượt quá tồn kho hiện tại.');
        }

        $giohang = $this->layGioHang();
        $id = $sanpham->id;

        if (isset($giohang[$id])) {
            $soLuongMoi = $giohang[$id]['so_luong'] + $soLuong;

            if ($soLuongMoi > $sanpham->so_luong_ton) {
                throw new \Exception('Số lượng trong giỏ vượt quá tồn kho.');
            }

            $giohang[$id]['so_luong'] = $soLuongMoi;
            $giohang[$id]['don_gia'] = $sanpham->giaHienTai();
            $giohang[$id]['ton_kho'] = $sanpham->so_luong_ton;
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

        return $giohang;
    }

    public function capNhatSoLuong(array $soluong): array
    {
        $giohang = $this->layGioHang();

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

        return $giohang;
    }

    public function xoaSanPham(Sanpham $sanpham): void
    {
        $giohang = $this->layGioHang();

        unset($giohang[$sanpham->id]);

        session()->put('giohang', $giohang);
    }

    public function xoaTatCa(): void
    {
        session()->forget('giohang');
    }

    public function kiemTraGioHangRong(): bool
    {
        return empty($this->layGioHang());
    }
}
