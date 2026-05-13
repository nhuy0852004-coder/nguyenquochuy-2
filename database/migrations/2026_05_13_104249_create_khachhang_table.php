<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khachhang', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten', 150);
            $table->string('so_dien_thoai', 20)->index();
            $table->string('email', 150)->nullable()->index();
            $table->string('dia_chi', 500)->nullable();
            $table->timestamps();

            $table->index('ho_ten');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khachhang');
    }
};
