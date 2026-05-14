<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ThongkeService;

class BangdieukhienController extends Controller
{
    public function __construct(
        private ThongkeService $thongkeService
    ) {
        //
    }

    public function index()
    {
        $dulieu = $this->thongkeService->layDuLieuDashboard();

        return view('admin.bangdieukhien.index', compact('dulieu'));
    }
}
