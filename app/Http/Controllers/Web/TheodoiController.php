<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Donhang;
use Illuminate\Http\Request;

class TheodoiController extends Controller
{
    public function index()
    {
        return view('web.theodoi.index');
    }

    public function timkiem(Request $request)
    {
        $request->validate(
            [
                'tu_khoa' => ['required', 'string', 'max:100'],
            ],
            [
                'tu_khoa.required' => 'Vui lòng nhập mã đơn hàng hoặc số điện thoại.',
                'tu_khoa.max' => 'Thông tin tra cứu không được vượt quá 100 ký tự.',
            ]
        );

        $tukhoa = trim($request->tu_khoa);

        $danhsachdonhang = Donhang::query()
            ->with('chitietdonhang')
            ->where('ma_don_hang', $tukhoa)
            ->orWhere('so_dien_thoai_nguoi_nhan', $tukhoa)
            ->orderByDesc('id')
            ->get();

        return view('web.theodoi.ketqua', compact('danhsachdonhang', 'tukhoa'));
    }

    public function chitiet(string $madonhang)
    {
        $donhang = Donhang::query()
            ->with('chitietdonhang')
            ->where('ma_don_hang', $madonhang)
            ->firstOrFail();

        $timeline = $this->taoTimeline($donhang->trang_thai_don_hang);

        return view('web.theodoi.chitiet', compact('donhang', 'timeline'));
    }

    private function taoTimeline(string $trangThaiHienTai): array
    {
        if ($trangThaiHienTai === Donhang::TRANG_THAI_DA_HUY) {
            return [
                [
                    'key' => Donhang::TRANG_THAI_CHO_XAC_NHAN,
                    'label' => 'Chờ xác nhận',
                    'mo_ta' => 'Đơn hàng đã được tạo thành công.',
                    'active' => true,
                    'done' => true,
                ],
                [
                    'key' => Donhang::TRANG_THAI_DA_HUY,
                    'label' => 'Đã hủy',
                    'mo_ta' => 'Đơn hàng đã bị hủy.',
                    'active' => true,
                    'done' => true,
                ],
            ];
        }

        $cacTrangThai = [
            Donhang::TRANG_THAI_CHO_XAC_NHAN => [
                'label' => 'Chờ xác nhận',
                'mo_ta' => 'Cửa hàng đã nhận đơn và đang chờ xác nhận.',
            ],
            Donhang::TRANG_THAI_DA_XAC_NHAN => [
                'label' => 'Đã xác nhận',
                'mo_ta' => 'Đơn hàng đã được cửa hàng xác nhận.',
            ],
            Donhang::TRANG_THAI_DANG_GIAO_HANG => [
                'label' => 'Đang giao hàng',
                'mo_ta' => 'Đơn hàng đang được giao đến bạn.',
            ],
            Donhang::TRANG_THAI_HOAN_THANH => [
                'label' => 'Hoàn thành',
                'mo_ta' => 'Đơn hàng đã hoàn thành.',
            ],
        ];

        $keys = array_keys($cacTrangThai);
        $viTriHienTai = array_search($trangThaiHienTai, $keys);

        if ($viTriHienTai === false) {
            $viTriHienTai = 0;
        }

        $timeline = [];

        foreach ($cacTrangThai as $key => $item) {
            $viTri = array_search($key, $keys);

            $timeline[] = [
                'key' => $key,
                'label' => $item['label'],
                'mo_ta' => $item['mo_ta'],
                'active' => $viTri === $viTriHienTai,
                'done' => $viTri <= $viTriHienTai,
            ];
        }

        return $timeline;
    }
}
