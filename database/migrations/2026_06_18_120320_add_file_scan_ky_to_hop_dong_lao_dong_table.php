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
            // Thêm cột file_scan_ky kiểu chuỗi, cho phép null, nằm sau cột trang_thai_ky
            $table->string('file_scan_ky')->nullable()->after('trang_thai_ky');
        });
    }

    public function down(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            $table->dropColumn('file_scan_ky');
        });
    }
};
