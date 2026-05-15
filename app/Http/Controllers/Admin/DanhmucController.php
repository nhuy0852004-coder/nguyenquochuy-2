<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DanhmucRequest;
use App\Models\Danhmuc;
use App\Services\DanhmucService;
use Illuminate\Http\Request;

class DanhmucController extends Controller
{
    public function __construct(
        private DanhmucService $danhmucService
    ) {
        //
    }

    public function index(Request $request)
    {
        $tukhoa = $request->input('tu_khoa');
        $parentId = $request->input('parent_id');
        $kieuDanhMuc = $request->input('kieu_danh_muc');
        $trangthai = $request->input('trang_thai');
        $soLuongSanPham = $request->input('so_luong_san_pham');
        $sapxep = $request->input('sap_xep', 'thu_tu');
        $cotSapXep = $request->input('cot_sap_xep', 'thu_tu');
        $huongSapXep = $request->input('huong_sap_xep', 'asc');

        $cotHopLe = ['id', 'ten_danh_muc', 'thu_tu', 'trang_thai', 'created_at', 'sanpham_count', 'con_count'];

        if (! in_array($cotSapXep, $cotHopLe, true)) {
            $cotSapXep = 'thu_tu';
        }

        if (! in_array(strtolower($huongSapXep), ['asc', 'desc'], true)) {
            $huongSapXep = 'asc';
        }

        $danhsachdanhmuc = $this->danhmucService->layDanhSach(
            $tukhoa,
            $parentId,
            $kieuDanhMuc,
            $trangthai,
            $soLuongSanPham,
            $sapxep,
            $cotSapXep,
            $huongSapXep
        );

        $caydanhmuc = $this->danhmucService->layCayDanhMuc();
        $tatcadanhmuc = $this->danhmucService->layTatCaDanhMuc();

        if ($request->ajax()) {
            return view('admin.danhmuc._ketqua', compact(
                'danhsachdanhmuc',
                'caydanhmuc',
                'tatcadanhmuc',
                'tukhoa',
                'parentId',
                'kieuDanhMuc',
                'trangthai',
                'soLuongSanPham',
                'sapxep',
                'cotSapXep',
                'huongSapXep'
            ));
        }

        return view('admin.danhmuc.index', compact(
            'danhsachdanhmuc',
            'caydanhmuc',
            'tatcadanhmuc',
            'tukhoa',
            'parentId',
            'kieuDanhMuc',
            'trangthai',
            'soLuongSanPham',
            'sapxep',
            'cotSapXep',
            'huongSapXep'
        ));
    }

    public function store(DanhmucRequest $request)
    {
        $this->danhmucService->taoDanhMuc(
            $request->validated(),
            $request->has('trang_thai')
        );

        return redirect()
            ->route('admin.danhmuc.index')
            ->with('thanhcong', 'Thêm danh mục thành công.');
    }

    public function update(DanhmucRequest $request, Danhmuc $danhmuc)
    {
        $this->danhmucService->capNhatDanhMuc(
            $danhmuc,
            $request->validated(),
            $request->has('trang_thai')
        );

        return redirect()
            ->route('admin.danhmuc.index')
            ->with('thanhcong', 'Cập nhật danh mục thành công.');
    }

    public function destroy(Danhmuc $danhmuc)
    {
        try {
            $this->danhmucService->xoaDanhMuc($danhmuc);

            return redirect()
                ->route('admin.danhmuc.index')
                ->with('thanhcong', 'Xóa danh mục thành công.');
        } catch (\Exception $exception) {
            return redirect()
                ->route('admin.danhmuc.index')
                ->with('loi', $exception->getMessage());
        }
    }

    public function doitrangthai(Danhmuc $danhmuc)
    {
        $this->danhmucService->doiTrangThai($danhmuc);

        return redirect()
            ->route('admin.danhmuc.index')
            ->with('thanhcong', 'Cập nhật trạng thái danh mục thành công.');
    }
}
