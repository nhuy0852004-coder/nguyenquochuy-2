<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BangdieukhienController;
use App\Http\Controllers\Web\TrangchuController;

Route::get('/', [TrangchuController::class, 'index'])->name('web.trangchu');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [BangdieukhienController::class, 'index'])->name('bangdieukhien');
});
