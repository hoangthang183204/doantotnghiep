<?php
// database/migrations/2026_07_21_xxxxxx_add_thoi_gian_gui_to_hop_dong_lao_dong.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            if (!Schema::hasColumn('hop_dong_lao_dong', 'thoi_gian_gui')) {
                $table->timestamp('thoi_gian_gui')->nullable()->after('thoi_gian_duyet');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            if (Schema::hasColumn('hop_dong_lao_dong', 'thoi_gian_gui')) {
                $table->dropColumn('thoi_gian_gui');
            }
        });
    }
};