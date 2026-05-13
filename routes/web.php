<?php

use App\Http\Controllers\Admin\BangdieukhienController;
use App\Http\Controllers\Admin\DanhmucController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.bangdieukhien');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.bangdieukhien');
    })->name('index');

    Route::get('/bang-dieu-khien', [BangdieukhienController::class, 'index'])->name('bangdieukhien');

    Route::prefix('danh-muc')->name('danhmuc.')->group(function () {
        Route::get('/', [DanhmucController::class, 'index'])->name('index');
        Route::post('/', [DanhmucController::class, 'store'])->name('store');
        Route::put('/{danhmuc}', [DanhmucController::class, 'update'])->name('update');
        Route::delete('/{danhmuc}', [DanhmucController::class, 'destroy'])->name('destroy');
        Route::patch('/{danhmuc}/doi-trang-thai', [DanhmucController::class, 'doitrangthai'])->name('doitrangthai');
    });
});
