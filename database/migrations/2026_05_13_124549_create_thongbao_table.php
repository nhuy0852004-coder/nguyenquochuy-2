<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thongbao', function (Blueprint $table) {
            $table->id();

            $table->string('tieu_de', 200);
            $table->text('noi_dung');
            $table->string('loai', 80)->default('hethong');

            $table->string('duong_dan')->nullable();

            $table->boolean('da_doc')->default(false);
            $table->timestamp('doc_luc')->nullable();

            $table->unsignedBigInteger('donhang_id')->nullable();
            $table->unsignedBigInteger('sanpham_id')->nullable();

            $table->timestamps();

            $table->index('loai');
            $table->index('da_doc');
            $table->index('created_at');
            $table->index('donhang_id');
            $table->index('sanpham_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thongbao');
    }
};
