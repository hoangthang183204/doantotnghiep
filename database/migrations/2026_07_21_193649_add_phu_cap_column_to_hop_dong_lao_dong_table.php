<?php
// database/migrations/2026_07_21_xxxxxx_add_phu_cap_column_to_hop_dong_lao_dong.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            if (!Schema::hasColumn('hop_dong_lao_dong', 'phu_cap')) {
                $table->text('phu_cap')->nullable()->after('phu_cap_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            if (Schema::hasColumn('hop_dong_lao_dong', 'phu_cap')) {
                $table->dropColumn('phu_cap');
            }
        });
    }
};