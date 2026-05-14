<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Thongbao;
use App\Repositories\ThongbaoRepository;
use Illuminate\Http\Request;

class ThongbaoController extends Controller
{
    public function __construct(
        private ThongbaoRepository $thongbaoRepository
    ) {
        //
    }

    public function index(Request $request)
    {
        $trangthai = $request->input('trang_thai');
        $loai = $request->input('loai');

        $danhsachthongbao = $this->thongbaoRepository->locPhanTrang($trangthai, $loai);

        $soluongchuadoc = $this->thongbaoRepository->demChuaDoc();

        return view('admin.thongbao.index', compact(
            'danhsachthongbao',
            'soluongchuadoc',
            'trangthai',
            'loai'
        ));
    }

    public function danhDauDaDoc(Thongbao $thongbao)
    {
        $thongbao->danhDauDaDoc();
        cache()->forget('thongbao_chua_doc_count');
        cache()->forget('thongbao_moi_nhat');

        if ($thongbao->duong_dan) {
            return redirect($thongbao->duong_dan);
        }

        return redirect()
            ->route('admin.thongbao.index')
            ->with('thanhcong', 'Đã đánh dấu thông báo là đã đọc.');
    }

    public function danhDauTatCaDaDoc()
    {
        Thongbao::query()
            ->where('da_doc', false)
            ->update([
                'da_doc' => true,
                'doc_luc' => now(),
            ]);

        cache()->forget('thongbao_chua_doc_count');
        cache()->forget('thongbao_moi_nhat');

        return redirect()
            ->route('admin.thongbao.index')
            ->with('thanhcong', 'Đã đánh dấu tất cả thông báo là đã đọc.');
    }

    public function xoa(Thongbao $thongbao)
    {
        $thongbao->delete();
        cache()->forget('thongbao_chua_doc_count');
        cache()->forget('thongbao_moi_nhat');

        return redirect()
            ->route('admin.thongbao.index')
            ->with('thanhcong', 'Đã xóa thông báo.');
    }
}
