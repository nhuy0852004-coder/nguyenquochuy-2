<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BaocaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BaocaoController extends Controller
{
    public function __construct(
        private BaocaoService $baocaoService
    ) {
        //
    }

    public function index(Request $request)
    {
        $tuNgay = $request->input('tu_ngay')
            ? Carbon::parse($request->input('tu_ngay'))->startOfDay()
            : now()->startOfMonth();

        $denNgay = $request->input('den_ngay')
            ? Carbon::parse($request->input('den_ngay'))->endOfDay()
            : now()->endOfDay();

        if ($tuNgay->gt($denNgay)) {
            return redirect()
                ->route('admin.baocao.index')
                ->with('loi', 'Ngày bắt đầu không được lớn hơn ngày kết thúc.');
        }

        $baocao = $this->baocaoService->taoBaoCao($tuNgay, $denNgay);

        return view('admin.baocao.index', [
            'tuNgay' => $tuNgay,
            'denNgay' => $denNgay,
            'thongke' => $baocao['thongke'],
            'doanhThuNhanh' => $baocao['doanhThuNhanh'],
            'doanhThu7Ngay' => $baocao['doanhThu7Ngay'],
            'topSanPhamBanChay' => $baocao['topSanPhamBanChay'],
            'danhsachdonhang' => $baocao['danhsachdonhang'],
        ]);
    }
}
