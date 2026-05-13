<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caidatcuahang', function (Blueprint $table) {
            $table->id();

            $table->string('ten_cua_hang', 200)->default('Bán Hàng Việt');
            $table->string('logo')->nullable();

            $table->string('so_dien_thoai', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('dia_chi', 500)->nullable();

            $table->text('chinh_sach_van_chuyen')->nullable();
            $table->text('chinh_sach_doi_tra')->nullable();

            $table->string('facebook')->nullable();
            $table->string('zalo')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caidatcuahang');
    }
};
