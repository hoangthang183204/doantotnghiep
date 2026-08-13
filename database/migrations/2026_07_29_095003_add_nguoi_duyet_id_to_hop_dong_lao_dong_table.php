<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            // ⭐ SỬA: Bỏ after('trang_thai') vì cột không tồn tại
            // Hoặc thay bằng cột có sẵn như 'id', 'nguoi_ky_id', 'created_at'
            if (!Schema::hasColumn('hop_dong_lao_dong', 'nguoi_duyet_id')) {
                $table->unsignedBigInteger('nguoi_duyet_id')->nullable();
            }
        });

        // Thêm foreign key riêng để tránh lỗi
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            if (Schema::hasColumn('hop_dong_lao_dong', 'nguoi_duyet_id')) {
                $table->foreign('nguoi_duyet_id')->references('id')->on('nguoi_dung')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            if (Schema::hasColumn('hop_dong_lao_dong', 'nguoi_duyet_id')) {
                $table->dropForeign(['nguoi_duyet_id']);
                $table->dropColumn('nguoi_duyet_id');
            }
        });
    }
};