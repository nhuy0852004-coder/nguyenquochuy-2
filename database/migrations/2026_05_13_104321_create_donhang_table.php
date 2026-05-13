<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donhang', function (Blueprint $table) {
            $table->id();

            $table->foreignId('khachhang_id')
                ->constrained('khachhang')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('ma_don_hang', 30)->unique();

            $table->string('ho_ten_nguoi_nhan', 150);
            $table->string('so_dien_thoai_nguoi_nhan', 20);
            $table->string('email_nguoi_nhan', 150)->nullable();
            $table->string('dia_chi_giao_hang', 500);
            $table->text('ghi_chu')->nullable();

            $table->unsignedBigInteger('tam_tinh')->default(0);
            $table->unsignedBigInteger('phi_van_chuyen')->default(0);
            $table->unsignedBigInteger('tong_tien')->default(0);

            $table->string('phuong_thuc_thanh_toan', 50)->default('cod');
            $table->string('trang_thai_thanh_toan', 50)->default('chua_thanh_toan');

            $table->string('trang_thai_don_hang', 50)->default('cho_xac_nhan');

            $table->timestamps();

            $table->index('ma_don_hang');
            $table->index('so_dien_thoai_nguoi_nhan');
            $table->index('trang_thai_don_hang');
            $table->index('phuong_thuc_thanh_toan');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donhang');
    }
};
