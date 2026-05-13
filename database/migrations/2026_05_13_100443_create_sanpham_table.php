<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sanpham', function (Blueprint $table) {
            $table->id();

            $table->foreignId('danhmuc_id')
                ->constrained('danhmuc')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('ten_san_pham', 200);
            $table->string('duong_dan', 220)->unique();
            $table->string('ma_san_pham', 50)->unique()->nullable();

            $table->unsignedBigInteger('gia_ban')->default(0);
            $table->unsignedBigInteger('gia_khuyen_mai')->nullable();

            $table->unsignedInteger('so_luong_ton')->default(0);
            $table->unsignedInteger('muc_canh_bao_ton')->default(5);

            $table->string('anh_dai_dien')->nullable();
            $table->text('mo_ta_ngan')->nullable();
            $table->longText('mo_ta_chi_tiet')->nullable();

            $table->boolean('trang_thai')->default(true);
            $table->boolean('noi_bat')->default(false);

            $table->softDeletes();
            $table->timestamps();

            $table->index('danhmuc_id');
            $table->index('ten_san_pham');
            $table->index('gia_ban');
            $table->index('so_luong_ton');
            $table->index('trang_thai');
            $table->index('noi_bat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanpham');
    }
};
