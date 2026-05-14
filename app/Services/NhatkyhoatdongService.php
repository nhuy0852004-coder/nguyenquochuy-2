<?php

namespace App\Services;

use App\Models\Nhatkyhoatdong;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class NhatkyhoatdongService
{
    public function ghi(
        string $hanhDong,
        string $tieuDe,
        ?string $noiDung = null,
        ?string $doiTuong = null,
        ?int $doiTuongId = null,
        ?array $duLieuCu = null,
        ?array $duLieuMoi = null
    ): Nhatkyhoatdong {
        return Nhatkyhoatdong::create([
            'nguoidung_id' => Auth::id(),
            'hanh_dong' => $hanhDong,
            'doi_tuong' => $doiTuong,
            'doi_tuong_id' => $doiTuongId,
            'tieu_de' => $tieuDe,
            'noi_dung' => $noiDung,
            'dia_chi_ip' => Request::ip(),
            'trinh_duyet' => substr((string) Request::userAgent(), 0, 255),
            'du_lieu_cu' => $duLieuCu,
            'du_lieu_moi' => $duLieuMoi,
        ]);
    }

    public function ghiThem(Model $model, string $tieuDe, ?string $noiDung = null): Nhatkyhoatdong
    {
        return $this->ghi(
            Nhatkyhoatdong::HANH_DONG_THEM,
            $tieuDe,
            $noiDung,
            class_basename($model),
            $model->id,
            null,
            $this->locDuLieu($model->toArray())
        );
    }

    public function ghiSua(Model $model, array $duLieuCu, string $tieuDe, ?string $noiDung = null): Nhatkyhoatdong
    {
        return $this->ghi(
            Nhatkyhoatdong::HANH_DONG_SUA,
            $tieuDe,
            $noiDung,
            class_basename($model),
            $model->id,
            $this->locDuLieu($duLieuCu),
            $this->locDuLieu($model->fresh()?->toArray() ?? [])
        );
    }

    public function ghiXoa(Model $model, string $tieuDe, ?string $noiDung = null): Nhatkyhoatdong
    {
        return $this->ghi(
            Nhatkyhoatdong::HANH_DONG_XOA,
            $tieuDe,
            $noiDung,
            class_basename($model),
            $model->id,
            $this->locDuLieu($model->toArray()),
            null
        );
    }

    public function ghiDoiTrangThai(Model $model, array $duLieuCu, string $tieuDe, ?string $noiDung = null): Nhatkyhoatdong
    {
        return $this->ghi(
            Nhatkyhoatdong::HANH_DONG_DOI_TRANG_THAI,
            $tieuDe,
            $noiDung,
            class_basename($model),
            $model->id,
            $this->locDuLieu($duLieuCu),
            $this->locDuLieu($model->fresh()?->toArray() ?? [])
        );
    }

    public function ghiDangNhap(): Nhatkyhoatdong
    {
        return $this->ghi(
            Nhatkyhoatdong::HANH_DONG_DANG_NHAP,
            'Đăng nhập hệ thống',
            'Người dùng đăng nhập vào trang quản trị.'
        );
    }

    public function ghiDangXuat(): Nhatkyhoatdong
    {
        return $this->ghi(
            Nhatkyhoatdong::HANH_DONG_DANG_XUAT,
            'Đăng xuất hệ thống',
            'Người dùng đăng xuất khỏi trang quản trị.'
        );
    }

    private function locDuLieu(array $duLieu): array
    {
        unset(
            $duLieu['mat_khau'],
            $duLieu['password'],
            $duLieu['remember_token'],
            $duLieu['created_at'],
            $duLieu['updated_at']
        );

        return $duLieu;
    }
}
