<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donhang', function (Blueprint $table) {
            $table->string('ma_giao_dich_thanh_toan')->nullable()->after('trang_thai_thanh_toan');
            $table->string('ma_phan_hoi_thanh_toan')->nullable()->after('ma_giao_dich_thanh_toan');
            $table->string('cong_thanh_toan')->nullable()->after('ma_phan_hoi_thanh_toan');
            $table->timestamp('thanh_toan_luc')->nullable()->after('cong_thanh_toan');

            $table->index('ma_giao_dich_thanh_toan');
            $table->index('cong_thanh_toan');
        });
    }

    public function down(): void
    {
        Schema::table('donhang', function (Blueprint $table) {
            $table->dropIndex(['ma_giao_dich_thanh_toan']);
            $table->dropIndex(['cong_thanh_toan']);

            $table->dropColumn([
                'ma_giao_dich_thanh_toan',
                'ma_phan_hoi_thanh_toan',
                'cong_thanh_toan',
                'thanh_toan_luc',
            ]);
        });
    }
};
