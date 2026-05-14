<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nhatkyhoatdong', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('nguoidung_id')->nullable();

            $table->string('hanh_dong', 100);
            $table->string('doi_tuong', 100)->nullable();
            $table->unsignedBigInteger('doi_tuong_id')->nullable();

            $table->string('tieu_de', 255);
            $table->text('noi_dung')->nullable();

            $table->string('dia_chi_ip', 100)->nullable();
            $table->string('trinh_duyet')->nullable();

            $table->json('du_lieu_cu')->nullable();
            $table->json('du_lieu_moi')->nullable();

            $table->timestamps();

            $table->index('nguoidung_id');
            $table->index('hanh_dong');
            $table->index('doi_tuong');
            $table->index('doi_tuong_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nhatkyhoatdong');
    }
};
