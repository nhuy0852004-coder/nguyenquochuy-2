<?php

namespace App\Services;

use App\Models\Donhang;
use App\Models\Sanpham;
use App\Models\Thongbao;

class ThongbaoService
{
    public function taoThongBaoDonHangMoi(Donhang $donhang): Thongbao
    {
        $thongbao = Thongbao::create([
            'tieu_de' => 'Có đơn hàng mới ' . $donhang->ma_don_hang,
            'noi_dung' => 'Khách hàng ' . $donhang->ho_ten_nguoi_nhan . ' vừa đặt đơn hàng trị giá ' . number_format($donhang->tong_tien, 0, ',', '.') . ' ₫.',
            'loai' => Thongbao::LOAI_DON_HANG,
            'duong_dan' => route('admin.donhang.chitiet', $donhang),
            'donhang_id' => $donhang->id,
            'da_doc' => false,
        ]);

        cache()->forget('thongbao_chua_doc_count');
        cache()->forget('thongbao_moi_nhat');

        return $thongbao;
    }

    public function taoThongBaoDonHangBiHuy(Donhang $donhang): Thongbao
    {
        $thongbao = Thongbao::create([
            'tieu_de' => 'Đơn hàng đã bị hủy ' . $donhang->ma_don_hang,
            'noi_dung' => 'Đơn hàng của khách ' . $donhang->ho_ten_nguoi_nhan . ' đã chuyển sang trạng thái Đã hủy.',
            'loai' => Thongbao::LOAI_DON_HANG,
            'duong_dan' => route('admin.donhang.chitiet', $donhang),
            'donhang_id' => $donhang->id,
            'da_doc' => false,
        ]);

        cache()->forget('thongbao_chua_doc_count');
        cache()->forget('thongbao_moi_nhat');

        return $thongbao;
    }

    public function taoThongBaoSanPhamGanHet(Sanpham $sanpham): ?Thongbao
    {
        if ($sanpham->so_luong_ton > $sanpham->muc_canh_bao_ton) {
            return null;
        }

        $daCoThongBaoGanDay = Thongbao::query()
            ->where('loai', Thongbao::LOAI_TON_KHO)
            ->where('sanpham_id', $sanpham->id)
            ->whereDate('created_at', now()->toDateString())
            ->exists();

        if ($daCoThongBaoGanDay) {
            return null;
        }

        $noiDungTonKho = $sanpham->so_luong_ton <= 0
            ? 'Sản phẩm đã hết hàng.'
            : 'Sản phẩm chỉ còn ' . $sanpham->so_luong_ton . ' trong kho.';

        $thongbao = Thongbao::create([
            'tieu_de' => 'Sản phẩm gần hết hàng',
            'noi_dung' => $noiDungTonKho . ' Sản phẩm: ' . $sanpham->ten_san_pham,
            'loai' => Thongbao::LOAI_TON_KHO,
            'duong_dan' => route('admin.sanpham.index', ['tu_khoa' => $sanpham->ten_san_pham]),
            'sanpham_id' => $sanpham->id,
            'da_doc' => false,
        ]);

        cache()->forget('thongbao_chua_doc_count');
        cache()->forget('thongbao_moi_nhat');

        return $thongbao;
    }
}
