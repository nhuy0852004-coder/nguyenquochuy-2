<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chitietdonhang', function (Blueprint $table) {
            $table->id();

            $table->foreignId('donhang_id')
                ->constrained('donhang')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('sanpham_id')
                ->constrained('sanpham')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('ten_san_pham', 200);
            $table->string('ma_san_pham', 50)->nullable();
            $table->string('anh_san_pham')->nullable();

            $table->unsignedBigInteger('don_gia')->default(0);
            $table->unsignedInteger('so_luong')->default(1);
            $table->unsignedBigInteger('thanh_tien')->default(0);

            $table->timestamps();

            $table->index('donhang_id');
            $table->index('sanpham_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chitietdonhang');
    }
};
