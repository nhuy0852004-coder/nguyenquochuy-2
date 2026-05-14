<?php

use App\Http\Controllers\Admin\BangdieukhienController;
use App\Http\Controllers\Admin\DanhmucController;
use App\Http\Controllers\Admin\DonhangController;
use App\Http\Controllers\Admin\KhachhangController;
use App\Http\Controllers\Admin\BaocaoController;
use App\Http\Controllers\Admin\CaidatcuahangController;
use App\Http\Controllers\Admin\SanphamController;
use App\Http\Controllers\Admin\ThongbaoController;
use App\Http\Controllers\Admin\XacthucController;
use App\Http\Controllers\Admin\NguoidungController;
use App\Http\Controllers\Web\GiohangController;
use App\Http\Controllers\Web\SanphamController as WebSanphamController;
use App\Http\Controllers\Web\ThanhtoanController;
use App\Http\Controllers\Web\TheodoiController;
use App\Http\Controllers\Web\TrangchuController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TrangchuController::class, 'index'])->name('web.trangchu');

Route::get('/san-pham', [WebSanphamController::class, 'index'])->name('web.sanpham.index');
Route::get('/san-pham/{duongdan}', [WebSanphamController::class, 'chitiet'])->name('web.sanpham.chitiet');

Route::get('/gio-hang', [GiohangController::class, 'index'])->name('web.giohang.index');
Route::post('/gio-hang/them/{sanpham}', [GiohangController::class, 'them'])->name('web.giohang.them');
Route::patch('/gio-hang/cap-nhat', [GiohangController::class, 'capnhat'])->name('web.giohang.capnhat');
Route::delete('/gio-hang/xoa/{sanpham}', [GiohangController::class, 'xoa'])->name('web.giohang.xoa');
Route::delete('/gio-hang/xoa-tat-ca', [GiohangController::class, 'xoaTatCa'])->name('web.giohang.xoatatca');

Route::get('/thanh-toan', [ThanhtoanController::class, 'index'])->name('web.thanhtoan.index');
Route::post('/thanh-toan/dat-hang', [ThanhtoanController::class, 'datHang'])->name('web.thanhtoan.dathang');
Route::get('/thanh-toan/thanh-cong/{madonhang}', [ThanhtoanController::class, 'thanhCong'])->name('web.thanhtoan.thanhcong');

Route::get('/theo-doi-don-hang', [TheodoiController::class, 'index'])->name('web.theodoi.index');
Route::post('/theo-doi-don-hang/tim-kiem', [TheodoiController::class, 'timkiem'])->name('web.theodoi.timkiem');
Route::get('/theo-doi-don-hang/{madonhang}', [TheodoiController::class, 'chitiet'])->name('web.theodoi.chitiet');

Route::get('/admin/dang-nhap', [XacthucController::class, 'hienThiDangNhap'])->name('admin.dangnhap');
Route::post('/admin/dang-nhap', [XacthucController::class, 'dangNhap'])->name('admin.xuly.dangnhap');
Route::post('/admin/dang-xuat', [XacthucController::class, 'dangXuat'])->name('admin.dangxuat');

Route::prefix('admin')
    ->name('admin.')
    ->middleware('kiemtraadmin')
    ->group(function () {
    Route::get('/', [BangdieukhienController::class, 'index'])->name('bangdieukhien');

    Route::get('/danhmuc', [DanhmucController::class, 'index'])->name('danhmuc.index');
    Route::post('/danhmuc', [DanhmucController::class, 'store'])->name('danhmuc.store');
    Route::put('/danhmuc/{danhmuc}', [DanhmucController::class, 'update'])->name('danhmuc.update');
    Route::delete('/danhmuc/{danhmuc}', [DanhmucController::class, 'destroy'])->name('danhmuc.destroy');
    Route::patch('/danhmuc/{danhmuc}/doi-trang-thai', [DanhmucController::class, 'doitrangthai'])->name('danhmuc.doitrangthai');

    Route::get('/sanpham', [SanphamController::class, 'index'])->name('sanpham.index');
    Route::post('/sanpham', [SanphamController::class, 'store'])->name('sanpham.store');
    Route::put('/sanpham/{sanpham}', [SanphamController::class, 'update'])->name('sanpham.update');
    Route::delete('/sanpham/{sanpham}', [SanphamController::class, 'destroy'])->name('sanpham.destroy');
    Route::patch('/sanpham/{sanpham}/doi-trang-thai', [SanphamController::class, 'doitrangthai'])->name('sanpham.doitrangthai');

    Route::get('/donhang', [DonhangController::class, 'index'])->name('donhang.index');
    Route::get('/donhang/{donhang}', [DonhangController::class, 'chitiet'])->name('donhang.chitiet');
    Route::patch('/donhang/{donhang}/cap-nhat-trang-thai', [DonhangController::class, 'capnhattrangthai'])->name('donhang.capnhattrangthai');

    Route::get('/khachhang', [KhachhangController::class, 'index'])->name('khachhang.index');
    Route::get('/khachhang/{khachhang}', [KhachhangController::class, 'chitiet'])->name('khachhang.chitiet');

    Route::get('/baocao', [BaocaoController::class, 'index'])->name('baocao.index');

    Route::get('/caidatcuahang', [CaidatcuahangController::class, 'index'])->name('caidatcuahang.index');
    Route::match(['POST', 'PUT', 'PATCH'], '/caidatcuahang', [CaidatcuahangController::class, 'capnhat'])->name('caidatcuahang.capnhat');
    Route::delete('/caidatcuahang/logo', [CaidatcuahangController::class, 'xoalogo'])->name('caidatcuahang.xoalogo');

    Route::get('/nguoidung', [NguoidungController::class, 'index'])->name('nguoidung.index');
    Route::post('/nguoidung', [NguoidungController::class, 'store'])->name('nguoidung.store');
    Route::put('/nguoidung/{nguoidung}', [NguoidungController::class, 'update'])->name('nguoidung.update');
    Route::patch('/nguoidung/{nguoidung}/doi-mat-khau', [NguoidungController::class, 'doiMatKhau'])->name('nguoidung.doimatkhau');
    Route::patch('/nguoidung/{nguoidung}/doi-trang-thai', [NguoidungController::class, 'doiTrangThai'])->name('nguoidung.doitrangthai');
    Route::delete('/nguoidung/{nguoidung}', [NguoidungController::class, 'destroy'])->name('nguoidung.destroy');

    Route::get('/thongbao', [ThongbaoController::class, 'index'])->name('thongbao.index');
    Route::patch('/thongbao/danh-dau-tat-ca-da-doc', [ThongbaoController::class, 'danhDauTatCaDaDoc'])->name('thongbao.daudoc.tatca');
    Route::patch('/thongbao/{thongbao}/da-doc', [ThongbaoController::class, 'danhDauDaDoc'])->name('thongbao.dadoc');
    Route::delete('/thongbao/{thongbao}', [ThongbaoController::class, 'xoa'])->name('thongbao.xoa');
    });
