<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ung_vien', function (Blueprint $table) {
            // Chuyển ENUM sang VARCHAR
            DB::statement("ALTER TABLE ung_vien MODIFY trang_thai VARCHAR(50) DEFAULT 'moi_nop'");
        });
    }

    public function down(): void
    {
        Schema::table('ung_vien', function (Blueprint $table) {
            // Rollback về ENUM (nếu cần)
            DB::statement("ALTER TABLE ung_vien MODIFY trang_thai ENUM('moi_nop', 'cho_duyet', 'da_duyet', 'dat', 'khong_dat') DEFAULT 'moi_nop'");
        });
    }
};