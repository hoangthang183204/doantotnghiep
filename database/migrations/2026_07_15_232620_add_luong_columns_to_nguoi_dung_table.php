<?php
// database/migrations/2026_07_15_000000_add_luong_columns_to_nguoi_dung_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nguoi_dung', function (Blueprint $table) {
            // ⭐ THÊM CỘT LƯƠNG CƠ BẢN
            if (!Schema::hasColumn('nguoi_dung', 'luong_co_ban')) {
                $table->decimal('luong_co_ban', 15, 2)->nullable()->after('trang_thai')->comment('Lương cơ bản');
            }
            
            // ⭐ THÊM CỘT LƯƠNG THEO GIỜ
            if (!Schema::hasColumn('nguoi_dung', 'luong_theo_gio')) {
                $table->decimal('luong_theo_gio', 15, 2)->nullable()->after('luong_co_ban')->comment('Lương theo giờ');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nguoi_dung', function (Blueprint $table) {
            $table->dropColumn(['luong_co_ban', 'luong_theo_gio']);
        });
    }
};