<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('danhmuc', function (Blueprint $table) {
            $table->id();
            $table->string('ten_danh_muc', 150);
            $table->string('duong_dan', 180)->unique();
            $table->text('mo_ta')->nullable();
            $table->unsignedInteger('thu_tu')->default(0);
            $table->boolean('trang_thai')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index('ten_danh_muc');
            $table->index('trang_thai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('danhmuc');
    }
};