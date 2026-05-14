<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donhang', function (Blueprint $table) {
            $table->index(['trang_thai_don_hang', 'created_at'], 'donhang_trangthai_created_index');
            $table->index(['khachhang_id', 'created_at'], 'donhang_khachhang_created_index');
            $table->index(['ma_don_hang', 'so_dien_thoai_nguoi_nhan'], 'donhang_ma_sdt_index');
        });

        Schema::table('chitietdonhang', function (Blueprint $table) {
            $table->index(['sanpham_id', 'donhang_id'], 'ctdh_sanpham_donhang_index');
        });

        Schema::table('sanpham', function (Blueprint $table) {
            $table->index(['trang_thai', 'danhmuc_id'], 'sanpham_trangthai_danhmuc_index');
            $table->index(['trang_thai', 'noi_bat'], 'sanpham_trangthai_noibat_index');
            $table->index(['trang_thai', 'created_at'], 'sanpham_trangthai_created_index');
        });

        Schema::table('thongbao', function (Blueprint $table) {
            $table->index(['da_doc', 'created_at'], 'thongbao_dadoc_created_index');
        });

        Schema::table('nhatkyhoatdong', function (Blueprint $table) {
            $table->index(['nguoidung_id', 'created_at'], 'nhatky_nguoidung_created_index');
            $table->index(['hanh_dong', 'created_at'], 'nhatky_hanhdong_created_index');
        });
    }

    public function down(): void
    {
        Schema::table('donhang', function (Blueprint $table) {
            $table->dropIndex('donhang_trangthai_created_index');
            $table->dropIndex('donhang_khachhang_created_index');
            $table->dropIndex('donhang_ma_sdt_index');
        });

        Schema::table('chitietdonhang', function (Blueprint $table) {
            $table->dropIndex('ctdh_sanpham_donhang_index');
        });

        Schema::table('sanpham', function (Blueprint $table) {
            $table->dropIndex('sanpham_trangthai_danhmuc_index');
            $table->dropIndex('sanpham_trangthai_noibat_index');
            $table->dropIndex('sanpham_trangthai_created_index');
        });

        Schema::table('thongbao', function (Blueprint $table) {
            $table->dropIndex('thongbao_dadoc_created_index');
        });

        Schema::table('nhatkyhoatdong', function (Blueprint $table) {
            $table->dropIndex('nhatky_nguoidung_created_index');
            $table->dropIndex('nhatky_hanhdong_created_index');
        });
    }
};
