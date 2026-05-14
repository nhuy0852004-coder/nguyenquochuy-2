<?php

namespace App\Services;

use App\Models\Nguoidung;
use App\Repositories\NguoidungRepository;
use Illuminate\Support\Facades\Hash;

class NguoidungService
{
    public function __construct(
        private NguoidungRepository $nguoidungRepository,
        private NhatkyhoatdongService $nhatkyhoatdongService
    ) {
        //
    }

    public function layDanhSach(?string $tukhoa = null)
    {
        return $this->nguoidungRepository->timKiemPhanTrang($tukhoa);
    }

    public function taoNguoiDung(array $dulieu, bool $trangThai): Nguoidung
    {
        $dulieu['mat_khau'] = Hash::make($dulieu['mat_khau']);
        $dulieu['trang_thai'] = $trangThai;

        unset($dulieu['mat_khau_confirmation']);

        $nguoidung = $this->nguoidungRepository->tao($dulieu);

        $this->nhatkyhoatdongService->ghiThem(
            $nguoidung,
            'Thêm người dùng',
            'Đã thêm tài khoản: ' . $nguoidung->email
        );

        return $nguoidung;
    }

    public function capNhatNguoiDung(Nguoidung $nguoidung, array $dulieu, bool $trangThai): bool
    {
        $duLieuCu = $nguoidung->toArray();

        $dulieu['trang_thai'] = $trangThai;

        $ketqua = $this->nguoidungRepository->capNhat($nguoidung, $dulieu);

        $this->nhatkyhoatdongService->ghiSua(
            $nguoidung,
            $duLieuCu,
            'Cập nhật người dùng',
            'Đã cập nhật tài khoản: ' . $nguoidung->fresh()->email
        );

        return $ketqua;
    }

    public function doiMatKhau(Nguoidung $nguoidung, string $matKhauMoi): bool
    {
        $ketqua = $this->nguoidungRepository->capNhat($nguoidung, [
            'mat_khau' => Hash::make($matKhauMoi),
        ]);

        $this->nhatkyhoatdongService->ghi(
            \App\Models\Nhatkyhoatdong::HANH_DONG_DOI_MAT_KHAU,
            'Đổi mật khẩu người dùng',
            'Đã đổi mật khẩu cho tài khoản: ' . $nguoidung->email,
            'Nguoidung',
            $nguoidung->id
        );

        return $ketqua;
    }

    public function doiTrangThai(Nguoidung $nguoidung): bool
    {
        if (auth()->id() === $nguoidung->id) {
            throw new \Exception('Bạn không thể tự khóa tài khoản đang đăng nhập.');
        }

        $duLieuCu = $nguoidung->toArray();

        $ketqua = $this->nguoidungRepository->capNhat($nguoidung, [
            'trang_thai' => !$nguoidung->trang_thai,
        ]);

        $this->nhatkyhoatdongService->ghiDoiTrangThai(
            $nguoidung,
            $duLieuCu,
            'Đổi trạng thái người dùng',
            'Đã đổi trạng thái tài khoản: ' . $nguoidung->fresh()->email
        );

        return $ketqua;
    }

    public function xoaNguoiDung(Nguoidung $nguoidung): bool
    {
        if (auth()->id() === $nguoidung->id) {
            throw new \Exception('Bạn không thể xóa tài khoản đang đăng nhập.');
        }

        $this->nhatkyhoatdongService->ghiXoa(
            $nguoidung,
            'Xóa người dùng',
            'Đã xóa tài khoản: ' . $nguoidung->email
        );

        return $this->nguoidungRepository->xoa($nguoidung);
    }
}
