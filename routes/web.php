<?php

use App\Http\Controllers\Admin\BangdieukhienController;
use App\Http\Controllers\Admin\DanhmucController;
use App\Http\Controllers\Admin\SanphamController;
use App\Http\Controllers\Web\GiohangController;
use App\Http\Controllers\Web\SanphamController as WebSanphamController;
use App\Http\Controllers\Web\ThanhtoanController;
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

Route::prefix('admin')->name('admin.')->group(function () {
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
});
