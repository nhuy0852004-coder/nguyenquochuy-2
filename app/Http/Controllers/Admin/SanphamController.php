<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SanphamRequest;
use App\Models\Sanpham;
use App\Repositories\DanhmucRepository;
use App\Services\SanphamService;
use Illuminate\Http\Request;

class SanphamController extends Controller
{
    public function __construct(
        private SanphamService $sanphamService,
        private DanhmucRepository $danhmucRepository
    ) {
        //
    }

    public function index(Request $request)
    {
        $tukhoa = $request->input('tu_khoa');
        $danhmucId = $request->input('danhmuc_id');
        $trangthai = $request->input('trang_thai');

        $danhsachdanhmuc = $this->danhmucRepository->layDanhMucDangBat();

        $danhsachsanpham = $this->sanphamService->layDanhSachAdmin(
            $tukhoa,
            $danhmucId,
            $trangthai
        );

        return view('admin.sanpham.index', compact(
            'danhsachsanpham',
            'danhsachdanhmuc',
            'tukhoa',
            'danhmucId',
            'trangthai'
        ));
    }

    public function store(SanphamRequest $request)
    {
        $this->sanphamService->taoSanPham(
            $request->validated(),
            $request->has('trang_thai'),
            $request->has('noi_bat'),
            $request->file('anh_dai_dien')
        );

        return redirect()
            ->route('admin.sanpham.index')
            ->with('thanhcong', 'Thêm sản phẩm thành công.');
    }

    public function update(SanphamRequest $request, Sanpham $sanpham)
    {
        $this->sanphamService->capNhatSanPham(
            $sanpham,
            $request->validated(),
            $request->has('trang_thai'),
            $request->has('noi_bat'),
            $request->file('anh_dai_dien')
        );

        return redirect()
            ->route('admin.sanpham.index')
            ->with('thanhcong', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Sanpham $sanpham)
    {
        $this->sanphamService->xoaSanPham($sanpham);

        return redirect()
            ->route('admin.sanpham.index')
            ->with('thanhcong', 'Xóa sản phẩm thành công.');
    }

    public function doitrangthai(Sanpham $sanpham)
    {
        $this->sanphamService->doiTrangThai($sanpham);

        return redirect()
            ->route('admin.sanpham.index')
            ->with('thanhcong', 'Cập nhật trạng thái sản phẩm thành công.');
    }
}