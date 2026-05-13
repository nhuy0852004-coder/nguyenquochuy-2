<?php

namespace App\Providers;

use App\Models\Caidatcuahang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class CaidatcuahangServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('web.*', function ($view) {
            $view->with('caidatcuahang', Caidatcuahang::hienTai());
        });
    }
}
