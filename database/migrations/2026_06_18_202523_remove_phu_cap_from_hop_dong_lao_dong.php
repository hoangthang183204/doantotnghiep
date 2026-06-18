<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            $table->dropColumn('phu_cap');
        });
    }

    public function down(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            // Phục hồi nếu rollback
            $table->decimal('phu_cap', 15, 2)->nullable()->after('luong_co_ban');
        });
    }
};