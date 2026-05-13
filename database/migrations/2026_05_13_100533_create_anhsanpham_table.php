<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anhsanpham', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sanpham_id')
                ->constrained('sanpham')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('duong_dan_anh');
            $table->unsignedInteger('thu_tu')->default(0);

            $table->timestamps();

            $table->index('sanpham_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anhsanpham');
    }
};
