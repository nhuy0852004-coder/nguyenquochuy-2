<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donhang', function (Blueprint $table) {
            $table->boolean('da_hoan_kho')->default(false)->after('trang_thai_don_hang');
            $table->timestamp('hoan_kho_luc')->nullable()->after('da_hoan_kho');

            $table->index('da_hoan_kho');
        });
    }

    public function down(): void
    {
        Schema::table('donhang', function (Blueprint $table) {
            $table->dropIndex(['da_hoan_kho']);
            $table->dropColumn(['da_hoan_kho', 'hoan_kho_luc']);
        });
    }
};
