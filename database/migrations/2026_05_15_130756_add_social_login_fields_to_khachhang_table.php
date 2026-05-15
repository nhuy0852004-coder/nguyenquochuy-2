<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('khachhang', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('remember_token');
            $table->string('facebook_id')->nullable()->after('google_id');
            $table->string('anh_dai_dien')->nullable()->after('facebook_id');

            $table->index('google_id');
            $table->index('facebook_id');
        });
    }

    public function down(): void
    {
        Schema::table('khachhang', function (Blueprint $table) {
            $table->dropIndex(['google_id']);
            $table->dropIndex(['facebook_id']);

            $table->dropColumn([
                'google_id',
                'facebook_id',
                'anh_dai_dien',
            ]);
        });
    }
};
