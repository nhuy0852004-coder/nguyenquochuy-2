<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donhang', function (Blueprint $table) {
            $table->string('ma_thanh_toan_payos')->nullable()->after('cong_thanh_toan');
            $table->text('link_thanh_toan')->nullable()->after('ma_thanh_toan_payos');

            $table->index('ma_thanh_toan_payos');
        });
    }

    public function down(): void
    {
        Schema::table('donhang', function (Blueprint $table) {
            $table->dropIndex(['ma_thanh_toan_payos']);
            $table->dropColumn(['ma_thanh_toan_payos', 'link_thanh_toan']);
        });
    }
};
