<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Thongbao;
use Illuminate\Http\Request;

class ThongbaoController extends Controller
{
    public function index(Request $request)
    {
        $trangthai = $request->input('trang_thai');
        $loai = $request->input('loai');

        $danhsachthongbao = Thongbao::query()
            ->when($trangthai === 'chua_doc', function ($query) {
                $query->where('da_doc', false);
            })
            ->when($trangthai === 'da_doc', function ($query) {
                $query->where('da_doc', true);
            })
            ->when($loai, function ($query) use ($loai) {
                $query->where('loai', $loai);
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $soluongchuadoc = Thongbao::query()
            ->where('da_doc', false)
            ->count();

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

        return redirect()
            ->route('admin.thongbao.index')
            ->with('thanhcong', 'Đã đánh dấu tất cả thông báo là đã đọc.');
    }

    public function xoa(Thongbao $thongbao)
    {
        $thongbao->delete();

        return redirect()
            ->route('admin.thongbao.index')
            ->with('thanhcong', 'Đã xóa thông báo.');
    }
}
