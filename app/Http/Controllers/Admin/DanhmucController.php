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

        $danhsachdanhmuc = $this->danhmucService->layDanhSach($tukhoa);

        return view('admin.danhmuc.index', compact('danhsachdanhmuc', 'tukhoa'));
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