<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('khachhang', function (Blueprint $table) {
            $table->string('mat_khau')->nullable()->after('email');
            $table->boolean('trang_thai')->default(true)->after('mat_khau');
            $table->timestamp('email_verified_at')->nullable()->after('trang_thai');
            $table->rememberToken();
        });
    }

    public function down(): void
    {
        Schema::table('khachhang', function (Blueprint $table) {
            $table->dropColumn([
                'mat_khau',
                'trang_thai',
                'email_verified_at',
                'remember_token',
            ]);
        });
    }
};
