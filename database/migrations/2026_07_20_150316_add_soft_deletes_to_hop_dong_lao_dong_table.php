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
            // 1. Thêm cột thoi_gian_duyet nếu chưa có
            if (!Schema::hasColumn('hop_dong_lao_dong', 'thoi_gian_duyet')) {
                $table->timestamp('thoi_gian_duyet')->nullable();
            }
    
            // 2. Thêm cột trang_thai_duyet nếu chưa có (Tránh lỗi Admin vừa gặp)
            if (!Schema::hasColumn('hop_dong_lao_dong', 'trang_thai_duyet')) {
                $table->string('trang_thai_duyet')->default('cho_duyet')->nullable();
            }
    
            // 3. Thêm cột deleted_at nếu chưa có
            if (!Schema::hasColumn('hop_dong_lao_dong', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Xóa cột deleted_at khi rollback
        });
    }
};
