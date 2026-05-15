<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CapnhattonkhoRequest;
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
        $tonkho = $request->input('ton_kho');
        $noibat = $request->input('noi_bat');
        $khuyenmai = $request->input('khuyen_mai');

        $danhsachdanhmuc = $this->danhmucRepository->layDanhMucDangBat();

        $danhsachsanpham = $this->sanphamService->layDanhSachAdmin(
            $tukhoa,
            $danhmucId,
            $trangthai,
            $tonkho,
            $noibat,
            $khuyenmai
        );

        return view('admin.sanpham.index', compact(
            'danhsachsanpham',
            'danhsachdanhmuc',
            'tukhoa',
            'danhmucId',
            'trangthai',
            'tonkho',
            'noibat',
            'khuyenmai'
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

    public function capnhattonkho(CapnhattonkhoRequest $request, Sanpham $sanpham)
    {
        $this->sanphamService->capNhatTonKho(
            $sanpham,
            $request->validated()
        );

        return redirect()
            ->route('admin.sanpham.index')
            ->with('thanhcong', 'Cập nhật tồn kho sản phẩm thành công.');
    }

    public function doinoibat(Sanpham $sanpham)
    {
        $this->sanphamService->doiNoiBat($sanpham);

        return redirect()
            ->route('admin.sanpham.index')
            ->with('thanhcong', 'Cập nhật trạng thái nổi bật thành công.');
    }

    public function nhanban(Sanpham $sanpham)
    {
        $sanphamMoi = $this->sanphamService->nhanBanSanPham($sanpham);

        return redirect()
            ->route('admin.sanpham.index', ['tu_khoa' => $sanphamMoi->ma_san_pham])
            ->with('thanhcong', 'Nhân bản sản phẩm thành công. Sản phẩm mới đang ở trạng thái ẩn.');
    }

    public function destroy(Sanpham $sanpham)
    {
        try {
            $this->sanphamService->xoaSanPham($sanpham);

            return redirect()
                ->route('admin.sanpham.index')
                ->with('thanhcong', 'Xóa sản phẩm thành công.');
        } catch (\Exception $exception) {
            return redirect()
                ->route('admin.sanpham.index')
                ->with('loi', $exception->getMessage());
        }
    }

    public function doitrangthai(Sanpham $sanpham)
    {
        $this->sanphamService->doiTrangThai($sanpham);

        return redirect()
            ->route('admin.sanpham.index')
            ->with('thanhcong', 'Cập nhật trạng thái sản phẩm thành công.');
    }
}
