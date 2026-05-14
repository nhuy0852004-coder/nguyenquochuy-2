<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nguoidung', function (Blueprint $table) {
            $table->id();

            $table->string('ho_ten', 150);
            $table->string('email', 150)->unique();
            $table->string('mat_khau');

            $table->string('vai_tro', 50)->default('admin');
            $table->boolean('trang_thai')->default(true);

            $table->rememberToken();
            $table->timestamps();

            $table->index('email');
            $table->index('vai_tro');
            $table->index('trang_thai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nguoidung');
    }
};
