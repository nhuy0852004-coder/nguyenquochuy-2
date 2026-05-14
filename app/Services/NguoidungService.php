<?php

namespace App\Services;

use App\Models\Nguoidung;
use App\Repositories\NguoidungRepository;
use Illuminate\Support\Facades\Hash;

class NguoidungService
{
    public function __construct(
        private NguoidungRepository $nguoidungRepository
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

        return $this->nguoidungRepository->tao($dulieu);
    }

    public function capNhatNguoiDung(Nguoidung $nguoidung, array $dulieu, bool $trangThai): bool
    {
        $dulieu['trang_thai'] = $trangThai;

        return $this->nguoidungRepository->capNhat($nguoidung, $dulieu);
    }

    public function doiMatKhau(Nguoidung $nguoidung, string $matKhauMoi): bool
    {
        return $this->nguoidungRepository->capNhat($nguoidung, [
            'mat_khau' => Hash::make($matKhauMoi),
        ]);
    }

    public function doiTrangThai(Nguoidung $nguoidung): bool
    {
        if (auth()->id() === $nguoidung->id) {
            throw new \Exception('Bạn không thể tự khóa tài khoản đang đăng nhập.');
        }

        return $this->nguoidungRepository->capNhat($nguoidung, [
            'trang_thai' => !$nguoidung->trang_thai,
        ]);
    }

    public function xoaNguoiDung(Nguoidung $nguoidung): bool
    {
        if (auth()->id() === $nguoidung->id) {
            throw new \Exception('Bạn không thể xóa tài khoản đang đăng nhập.');
        }

        return $this->nguoidungRepository->xoa($nguoidung);
    }
}
