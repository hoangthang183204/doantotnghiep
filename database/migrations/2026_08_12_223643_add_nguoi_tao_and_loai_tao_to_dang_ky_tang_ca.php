<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dang_ky_tang_ca', function (Blueprint $table) {
            // Kiểm tra và thêm cột nếu chưa tồn tại
            if (!Schema::hasColumn('dang_ky_tang_ca', 'nguoi_tao_id')) {
                $table->unsignedBigInteger('nguoi_tao_id')->nullable()->after('nguoi_dung_id');
                $table->foreign('nguoi_tao_id')->references('id')->on('nguoi_dung')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('dang_ky_tang_ca', 'loai_tao')) {
                $table->enum('loai_tao', ['nhan_vien', 'truong_phong'])->default('nhan_vien')->after('nguoi_tao_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dang_ky_tang_ca', function (Blueprint $table) {
            if (Schema::hasColumn('dang_ky_tang_ca', 'nguoi_tao_id')) {
                $table->dropForeign(['nguoi_tao_id']);
                $table->dropColumn('nguoi_tao_id');
            }
            if (Schema::hasColumn('dang_ky_tang_ca', 'loai_tao')) {
                $table->dropColumn('loai_tao');
            }
        });
    }
};