<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            if (!Schema::hasColumn('hop_dong_lao_dong', 'trang_thai_tai_ky')) {
                $table->enum('trang_thai_tai_ky', ['cho_tai_ky', 'da_tai_ky'])
                    ->default('cho_tai_ky')
                    ->after('trang_thai_ky')
                    ->comment('cho_tai_ky: Chờ tái ký, da_tai_ky: Đã tái ký');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            if (Schema::hasColumn('hop_dong_lao_dong', 'trang_thai_tai_ky')) {
                $table->dropColumn('trang_thai_tai_ky');
            }
        });
    }
};